<?php

namespace App\Services\Resume\DTO;

use App\Services\Resume\DTO\Enums\EducationLevel;
use App\Services\Resume\DTO\Enums\EmploymentType;
use App\Services\Resume\DTO\Enums\Experience;
use App\Services\Resume\DTO\Enums\Sex;
use App\Services\Resume\DTO\Enums\Sort;
use App\Services\Resume\DTO\Enums\TimePeriod;
use App\Services\Resume\DTO\Enums\WorkSchedule;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

readonly class SearchEmployeeResumeDto
{
    public function __construct(
        public ?string $searchWords = null,
        public ?string $excludeWords = null,
        public ?bool $onlyInResume = null,
        public ?bool $onlyInCompanyName = null,
        public ?bool $onlyInDescription = null,
        public ?bool $onlyWithSalary = null,
        public ?bool $onlyContinuousExperience = null,
        public ?string $region = null,
        public ?Sex $sex = null,
        public ?int $salaryMin = null,
        public ?int $salaryMax = null,
        public ?EducationLevel $educationLevel = null,
        public ?Experience $experience = null,
        public ?EmploymentType $employmentType = null,
        public ?WorkSchedule $workSchedule = null,
        public ?Sort $sort = null,
        public ?TimePeriod $timePeriod = null,
        public ?int $perPage = null
    ) {
    }

    public function getSearchWordsList(): Collection
    {
        return $this->explodeWords($this->searchWords ?? '');
    }

    public function getExcludeWordsList(): Collection
    {
        return $this->explodeWords($this->excludeWords ?? '');
    }

    private function explodeWords(string $input)
    {
        return Str::of($input)
            ->explode(' ')
            ->map(fn($phrase) => Str::of(Str::trim($phrase ?? ''))->explode(','))
            ->collapse()
            ->map(fn($word) => Str::lower($word))
            ->filter()
            ->filter(fn($item) => Str::length($item) > 1)
            ->values();
    }
}