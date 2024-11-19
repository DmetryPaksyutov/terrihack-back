<?php

namespace App\Services\Resume\DTO;

use Illuminate\Support\Carbon;

readonly class EmployeeResumeDto
{
    public function __construct(
        public ?string $id,
        public ?string $resumeId,
        public ?string $firstName,
        public ?string $lastName,
        public ?string $patronymic,
        public ?string $sex,
        public ?int $age,
        public ?Carbon $dateOfBirth,
        public ?Carbon $resumeUpdatedAt,
        public ?string $phone,
        public ?string $email,
        public ?string $linkedin,
        public ?string $telegram,
        public ?string $city,
        public ?string $country,
        public ?array $education,
        public ?string $citizenship,
        public ?string $position,
        public ?int $expectedSalary,
        public ?string $expectedSalaryCurrency,
        public ?string $workExperienceInMonths,
        public ?string $isWorkExperienceContinuous,
        public ?array $workExperience,
        public ?array $skills,
        public ?array $languages,
        public ?array $personalQualities,
    )
    {
    }

    public function copyWithId(string $id): EmployeeResumeDto
    {
        return new self(
            id: $id,
            resumeId: $this->resumeId,
            firstName: $this->firstName,
            lastName: $this->lastName,
            patronymic: $this->patronymic,
            sex: $this->sex,
            age: $this->age,
            dateOfBirth: $this->dateOfBirth,
            resumeUpdatedAt: $this->resumeUpdatedAt,
            phone: $this->phone,
            email: $this->email,
            linkedin: $this->linkedin,
            telegram: $this->telegram,
            city: $this->city,
            country: $this->country,
            education: $this->education,
            citizenship: $this->citizenship,
            position: $this->position,
            expectedSalary: $this->expectedSalary,
            expectedSalaryCurrency: $this->expectedSalaryCurrency,
            workExperienceInMonths: $this->workExperienceInMonths,
            isWorkExperienceContinuous: $this->isWorkExperienceContinuous,
            workExperience: $this->workExperience,
            skills: $this->skills,
            languages: $this->languages,
            personalQualities: $this->personalQualities,
        );
    }
}