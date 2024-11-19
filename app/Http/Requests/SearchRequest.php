<?php

namespace App\Http\Requests;

use App\Services\Resume\DTO\Enums\EducationLevel;
use App\Services\Resume\DTO\Enums\EmploymentType;
use App\Services\Resume\DTO\Enums\Experience;
use App\Services\Resume\DTO\Enums\Sex;
use App\Services\Resume\DTO\Enums\Sort;
use App\Services\Resume\DTO\Enums\TimePeriod;
use App\Services\Resume\DTO\Enums\WorkSchedule;
use App\Services\Resume\DTO\SearchEmployeeResumeDto;
use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{
    public const RULES = [
        'search_words' => 'nullable|string|max:255',
        'exclude_words' => 'nullable|string|max:255',
        'only_in_resume' => 'nullable|boolean',
        'sex' => 'nullable|in:male,female',
        'only_in_company_name' => 'nullable|boolean',
        'only_in_description' => 'nullable|boolean',
        'only_with_salary' => 'nullable|boolean',
        'continuous_experience' => 'nullable|boolean',
        'region' => 'nullable|string|max:100',
        'salary_min' => 'nullable|integer|min:0',
        'salary_max' => 'nullable|integer|min:0',
        'education_level' => 'nullable|string|in:secondary,higher',
        'experience' => 'nullable|string|in:noExperience,1-3,3-6,6+',
        'employment_type' => 'nullable|string|in:fullTime,partTime,projectBased,volunteering,civilLawContract,partTime,internship',
        'work_schedule' => 'nullable|string|in:fullDay,shift,flexible,remote,rotational',
        'sort' => 'nullable|string|in:relevance,byUpdatedAt,bySalaryDesc,bySalaryAsc',
        'time_period' => 'nullable|string|in:allTime,month,week,3days,lastDay',
        'per_page' => 'nullable|integer|min:2|max:250',
    ];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return self::RULES;
    }

    public function getDto(): SearchEmployeeResumeDto
    {
        return new SearchEmployeeResumeDto(
            searchWords: $this->input('search_words'),
            excludeWords: $this->input('exclude_words'),
            onlyInResume: $this->input('only_in_resume'),
            onlyInCompanyName: $this->input('only_in_company_name'),
            onlyInDescription: $this->input('only_in_description'),
            onlyWithSalary: $this->input('only_with_salary'),
            onlyContinuousExperience: $this->input('continuous_experience'),
            region: $this->input('region'),
            sex: $this->input('sex') ? Sex::from($this->input('sex')) : null,
            salaryMin: $this->input('salary_min'),
            salaryMax: $this->input('salary_max'),
            educationLevel: $this->input('education_level')
                ? EducationLevel::from($this->input('education_level'))
                : null,
            experience: $this->input('experience')
                ? Experience::from($this->input('experience'))
                : null,
            employmentType: $this->input('employment_type')
                ? EmploymentType::from($this->input('employment_type'))
                : null,
            workSchedule: $this->input('work_schedule')
                ? WorkSchedule::from($this->input('work_schedule'))
                : null,
            sort: $this->input('sort')
                ? Sort::from($this->input('sort'))
                : null,
            timePeriod: $this->input('time_period')
                ? TimePeriod::from($this->input('time_period'))
                : null,
            perPage: $this->input('per_page'),
        );
    }
}
