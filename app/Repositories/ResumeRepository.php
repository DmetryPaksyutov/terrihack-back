<?php

namespace App\Repositories;

use App\Models\EmployeeResume;
use App\Services\Resume\Contracts\ResumeRepoContract;
use App\Services\Resume\DTO\EducationDto;
use App\Services\Resume\DTO\EmployeeResumeDto;
use App\Services\Resume\DTO\Enums\Experience;
use App\Services\Resume\DTO\Enums\Sort;
use App\Services\Resume\DTO\Enums\TimePeriod;
use App\Services\Resume\DTO\LanguageDto;
use App\Services\Resume\DTO\SearchEmployeeResumeDto;
use App\Services\Resume\DTO\WorkExperienceDto;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ResumeRepository implements ResumeRepoContract
{
    private const MONTHS_IN_YEAR = 12;

    public function createEmployeeResume(EmployeeResumeDto $dto): EmployeeResumeDto
    {
        $model = EmployeeResume::query()->create([
            'resume_id' => $dto->resumeId,
            'first_name' => $dto->firstName ? Str::lower($dto->firstName) : null,
            'last_name' => $dto->lastName ? Str::lower($dto->lastName) : null,
            'patronymic' => $dto->patronymic ? Str::lower($dto->patronymic) : null,
            'sex' => $dto->sex,
            'age' => $dto->age,
            'date_of_birth' => $dto->dateOfBirth,
            'phone' => $dto->phone,
            'email' => $dto->email ? Str::lower($dto->email) : null,
            'linkedin' => $dto->linkedin,
            'telegram' => $dto->telegram,
            'city' => $dto->city ? Str::lower($dto->city) : null,
            'country' => $dto->country ? Str::lower($dto->country) : null,
            'citizenship' => $dto->citizenship ? Str::lower($dto->citizenship) : null,
            'position' => $dto->position ? Str::lower($dto->position) : null,
            'expected_salary' => $dto->expectedSalary,
            'expected_salary_currency' => $dto->expectedSalaryCurrency
                ? Str::lower($dto->expectedSalaryCurrency)
                : $dto->expectedSalaryCurrency,
            'education' => collect($dto->education)->map(fn(EducationDto $item) => $item->toArray()),
            'work_experience' => collect($dto->workExperience)->map(fn(WorkExperienceDto $item) => $item->toArray()),
            'skills' => $dto->skills,
            'languages' => collect($dto->languages)->map(fn(LanguageDto $item) => $item->toArray()),
            'personal_qualities' => $dto->personalQualities,
            'resume_updated_at' => $dto->resumeUpdatedAt,
            'work_experience_in_months' => $dto->workExperienceInMonths,
            'is_work_experience_continuous' => $dto->isWorkExperienceContinuous,
        ]);

        return $dto->copyWithId($model->id);
    }

    public function searchByEmployeeResume(SearchEmployeeResumeDto $dto): LengthAwarePaginator
    {
        return EmployeeResume::query()
            ->when(
                $dto->region,
                fn($query) => $query
                    ->whereLike(['country' => $dto->region])
                    ->orWhereLike(['city' => $dto->region])
                    ->orWhereLike(['citizenship' => $dto->region])
            )
            ->when($dto->onlyWithSalary, fn($query) => $query->whereNotNull('salary'))
            ->when($dto->onlyContinuousExperience, fn($query) => $query->where(['is_work_experience_continuous' => true])
            )
            ->when($dto->sex, fn($query) => $query->orWhere(['sex' => $dto->sex->value])
            )
            ->when($dto->salaryMax, fn($query) => $query->where('salary', '<=', $dto->salaryMax))
            ->when($dto->salaryMin, fn($query) => $query->where('salary', '>=', $dto->salaryMin))
            ->when($dto->sort, fn($query) => match ($dto->sort) {
                Sort::RELEVANCE => $query,
                Sort::BY_UPDATED_AT => $query->orderBy('resume_updated_at')->orderBy('updated_at'),
                Sort::BY_SALARY_DESC => $query->orderByDesc('expected_salary'),
                Sort::BY_SALARY_ASC => $query->orderByAsc('expected_salary'),
            })
            ->when($dto->timePeriod, fn($query) => match ($dto->timePeriod) {
                TimePeriod::ALL_TIME => $query,
                TimePeriod::MONTH => $query
                    ->where('resume_updated_at', '>=', Carbon::now()->subMonth())
                    ->orWhere('created_at', '>=', Carbon::now()->subMonth()),
                TimePeriod::WEEK => $query
                    ->where('resume_updated_at', '>=', Carbon::now()->subWeek())
                    ->orWhere('created_at', '>=', Carbon::now()->subWeek()),
                TimePeriod::THREE_DAYS => $query
                    ->where('resume_updated_at', '>=', Carbon::now()->subDays(3))
                    ->orWhere('created_at', '>=', Carbon::now()->subDays(3)),
                TimePeriod::LAST_DAY => $query
                    ->where('resume_updated_at', '>=', Carbon::now()->subDay())
                    ->orWhere('created_at', '>=', Carbon::now()->subDay()),
            })
            ->when($dto->experience, fn($query) => match ($dto->experience) {
                Experience::NO_EXPERIENCE => $query,
                Experience::ONE_TO_THREE_YEARS => $query->where(fn($query) => $query
                    ->where('work_experience_in_months', '>=', self::MONTHS_IN_YEAR)
                    ->where('work_experience_in_months', '<=', self::MONTHS_IN_YEAR * 3)
                ),
                Experience::THREE_TO_SIX_YEARS => $query->where(fn($query) => $query
                    ->where('work_experience_in_months', '>=', self::MONTHS_IN_YEAR * 3)
                    ->where('work_experience_in_months', '<=', self::MONTHS_IN_YEAR * 6)
                ),
                Experience::SIX_PLUS_YEARS => $query->where(fn($query) => $query
                    ->where('work_experience_in_months', '>=', self::MONTHS_IN_YEAR * 6)
                ),
            })
            ->when($dto->getSearchWordsList()->isNotEmpty(), function ($query) use ($dto) {
                $query->where(function ($query) use ($dto) {
                    collect([
                        'first_name',
                        'last_name',
                        'patronymic',
                        'position',
                        'age',
                        'phone',
                        'email',
                        'linkedin',
                        'telegram',
                        'citizenship',
                        'country',
                        'city',
                        'expected_salary',
                        'expected_salary_currency',
                    ])->each(function ($field) use ($dto, $query) {
                        $query->orWhere(function ($query) use ($dto, $field) {
                            $dto->getSearchWordsList()->each(fn($word) => $query->orWhere($field, 'LIKE', "%$word%"));
                        });
                    });

                    collect([
                        'skills',
                        'work_experience',
                        'languages',
                        'personal_qualities',
                        'education',
                    ])->each(function ($field) use ($dto, $query) {
                        $query->orWhere(function ($query) use ($dto, $field) {
                            $dto
                                ->getSearchWordsList()
                                ->each(fn($word) => $query
                                    ->orWhere($field, 'LIKE',
                                        '%'
                                        . Str::of(json_encode(Str::of($word)->substr(1, -1)))
                                            ->substr(1, -1)
                                        . '%'
                                    )
                                );
                        });
                    });
                });
            })
            ->when($dto->getExcludeWordsList()->isNotEmpty(), function ($query) use ($dto) {
                $query->whereNotIn('id', function ($query) use ($dto) {
                    $query->select('id')->from(EmployeeResume::TABLE)->where(function ($query) use ($dto) {
                        collect([
                            'first_name',
                            'last_name',
                            'patronymic',
                            'position',
                            'age',
                            'phone',
                            'email',
                            'linkedin',
                            'telegram',
                            'citizenship',
                            'country',
                            'city',
                            'expected_salary',
                            'expected_salary_currency',
                        ])->each(function ($field) use ($dto, $query) {
                            $query->orWhere(function ($query) use ($dto, $field) {
                                $dto->getExcludeWordsList()->each(fn($word) => $query->orWhere($field, 'LIKE', "%$word%"));
                            });
                        });

                        collect([
                            'skills',
                            'work_experience',
                            'languages',
                            'personal_qualities',
                            'education',
                        ])->each(function ($field) use ($dto, $query) {
                            $query->orWhere(function ($query) use ($dto, $field) {
                                $dto
                                    ->getExcludeWordsList()
                                    ->each(fn($word) => $query
                                        ->orWhere($field, 'LIKE',
                                            '%'
                                            . Str::of(json_encode(Str::of($word)->substr(1, -1)))
                                                ->substr(1, -1)
                                            . '%'
                                        )
                                    );
                            });
                        });
                    });
                });
            })
            ->paginate($dto->perPage);
    }
}
