<?php

namespace App\Jobs;

use App\Models\Resume;
use App\Services\Resume\DTO\EducationDto;
use App\Services\Resume\DTO\EmployeeResumeDto;
use App\Services\Resume\DTO\LanguageDto;
use App\Services\Resume\DTO\WorkExperienceDto;
use App\Services\Resume\ResumeService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use OpenAI\Laravel\Facades\OpenAI;

class ParseFullNameAndAgeFromTxtJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public string $id)
    {
        $this->queue = 'ai';
    }

    public function handle(ResumeService $service): void
    {
        $resume = Resume::query()->findOrFail($this->id);
        $resumeText = File::get(storage_path($resume->txt_path));

        $data = Cache::remember($this->id, 600, fn() => $this->parseAll($resumeText));

        if (!$data) {
            Cache::delete($this->id);
            $resume->update([
                'status' => 'error',
                'statusText' => 'Произошла ошибка при обработке данных нейросетью',
            ]);
            return;
        }

        $service->createEmployeeResume($data);
    }

    protected function parseAll(string $text): ?EmployeeResumeDto
    {
        try {
            $response = OpenAI::chat()->create([
                'model' => env('OPENAI_MODEL'),
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => <<<PROMPT
                            Проанализируй текст и заполни JSON
                            
                            Отчество обычно заканчивается на ...вич, ...вна
                            Имя может быть в самом начале, а может быть рядом с фразой "Резюме обновлено"
                            
                            Возраст может быть написан примерно так: 20 лет, 31 год, 45  лет
                            Возраст может быть написан просто цифрой без "лет" и "год", тогда его следует искать в начале текста
                            
                            Если полная дата рождения т.е. год+месяц+день не найдена - выставлять null 
                            
                            Если у автора резюме есть отчество, которое заканчивается на ...вич - это точно male
                            Если у автора резюме фамилия кончается на улы - это точно male
                            Если у автора резюме фамилия кончается на кызы - это точно female
                            
                            Список языков: английский, китайский, испанский, казахский, хинди, арабский, французский, русский, бенгали, португальский, урду, индонезийский, немецкий, японский, панджаби, корейский, итальянский, турецкий, вьетнамский, телугу, марийский, тамильский, персидский, татарский, тайский, голландский, полдружский, украинский
                            для proficiency выбери 1 значение из списка: (A1, A2, B1, B2, C1, C2), если не смог - укажи null
                            
                            Если человек владеет технологиями, пиши их в skills
                            
                            Формат даты: YYYY-MM-DD если нет месяца - замени на 01, если нет дня - замени на 01
                            
                            resumeUpdatedAt - дата обновления резюме, заполни, если найдёшь информацию об этом в тексте, такая дата обычно расположена после "Резюме обновлено:"
                            workExperienceInMonths - весь опыт работы в месяцах
                            isWorkExperienceContinuous - true если опыт не прерывался больше, чем на 6 месяцев
                            
                            Если не нашёл что-то - вместо значения пиши null
                            
                            Дай ответ в формате JSON: `{
                              "user": {
                                "firstName": "string",
                                "lastName": "string",
                                "patronymic": "string",
                                "sex": "male",
                                "age": 29,
                                "dateOfBirth": "1995-01-02",
                                "city": "Астана",
                                "country": "Казахстан",
                                "citizenship": "Казахстан",
                                "position": "Бухгалтер",
                                "expectedSalary": 200000,
                                "expectedSalaryCurrency": 'kzt',
                                "contactInfo": {
                                  "phone": "string",
                                  "email": "user@example.com",
                                  "linkedin": "string",
                                  "telegram": "string"
                                }
                              },
                              "resumeUpdatedAt": "2021-01-02 15:20:25",
                              "education": [
                                {
                                  "institutionName": "string",
                                  "degree": "string",
                                  "fieldOfStudy": "string",
                                  "startDate": "2024-11-09",
                                  "endDate": "2024-11-09"
                                }
                              ],
                              "workExperienceInMonths": 1,
                              "isWorkExperienceContinuous": true,
                              "workExperience": [
                                {
                                  "companyName": "string",
                                  "position": "string",
                                  "startDate": "2024-11-09",
                                  "endDate": "2024-10-09",
                                  "responsibilities": "string",
                                  "achievements": "string"
                                }
                              ],
                              "skills": [
                                "string"
                              ],
                              "languages": [
                                {
                                  "language": "string",
                                  "proficiency": "A1"
                                }
                              ],
                              "personalQualities": [
                                "string"
                              ]
                            }`
                            кроме JSON ничего не должно быть
                        PROMPT
                    ],
                    ['role' => 'user', 'content' => $text],
                ],
            ]);

            $content = $response->choices[0]->message->content;

            if (!$content) {
                return null;
            }

            $content = Str::swap(
                [
                    '```' => '',
                    'json' => '',
                ],
                $content,
            );

            $json = json_decode($content, true);

            return new EmployeeResumeDto(
                id: null,
                resumeId: $this->id,
                firstName: $json['user']['firstName'] ?? null,
                lastName: $json['user']['lastName'] ?? null,
                patronymic: $json['user']['patronymic'] ?? null,
                sex: $json['user']['sex'] ?? null,
                age: $json['user']['age'] ?? null,
                dateOfBirth: ($json['user']['dateOfBirth'] ?? null) ? Carbon::parse($json['user']['dateOfBirth']) : null,
                resumeUpdatedAt: ($json['resumeUpdatedAt'] ?? null) ? Carbon::parse($json['resumeUpdatedAt']) : null,
                phone: $json['user']['contactInfo']['phone'] ?? null,
                email: $json['user']['contactInfo']['email'] ?? null,
                linkedin: $json['user']['contactInfo']['linkedin'] ?? null,
                telegram: $json['user']['contactInfo']['telegram'] ?? null,
                city: $json['user']['city'] ?? null,
                country: $json['user']['country'] ?? null,
                education: $json['education']
                    ? collect($json['education'])
                        ->filter(fn($item) => !empty($item['institutionName']) && !empty($item['endDate']))
                        ->map(fn($edu) => new EducationDto(
                            institutionName: $edu['institutionName'],
                            degree: $edu['degree'] ?? null,
                            fieldOfStudy: $edu['fieldOfStudy'] ?? null,
                            startDate: $edu['startDate'] ?? null,
                            endDate: $edu['endDate'],
                        ))
                        ->values()
                        ->toArray()
                    : null,
                citizenship: $json['user']['citizenship'] ?? null,
                position: $json['user']['position'] ?? null,
                expectedSalary: $json['user']['expectedSalary'] ?? null,
                expectedSalaryCurrency: $json['user']['expectedSalaryCurrency'] ?? null,
                workExperienceInMonths: $json['workExperienceInMonths'] ?? null,
                isWorkExperienceContinuous: $json['isWorkExperienceContinuous'] ?? null,
                workExperience: $json['workExperience']
                    ? collect($json['workExperience'])
                        ->filter(fn($item) => !empty($item['companyName'])
                            && !empty($item['position'])
                            && !empty($item['startDate'])
                            && !empty($item['endDate'])
                        )
                        ->map(fn($workExp) => new WorkExperienceDto(
                            companyName: $workExp['companyName'],
                            position: $workExp['position'],
                            startDate: $workExp['startDate'],
                            endDate: $workExp['endDate'],
                        ))
                        ->values()
                        ->toArray()
                    : null,
                skills: collect($json['skills'] ?? [])->filter()->map(fn($item) => Str::lower($item))->values()->toArray(),
                languages: $json['languages']
                    ? collect($json['languages'])
                        ->filter(fn($lang) => !empty($lang['language']) && !empty($lang['proficiency']))
                        ->map(fn($lang) => new LanguageDto(
                            language: $lang['language'],
                            proficiency: $lang['proficiency'],
                        ))
                        ->values()
                        ->toArray()
                    : null,
                personalQualities: collect($json['personalQualities'] ?? [])->filter()->map(fn($item) => Str::lower($item))->values()->toArray(),
            );
        } catch (\Exception $exception) {
            Log::debug(self::class . ': ' . $exception->getMessage());
            return null;
        }
    }
}
