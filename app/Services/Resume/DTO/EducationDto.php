<?php

namespace App\Services\Resume\DTO;

use Illuminate\Support\Str;

readonly class EducationDto
{
    public function __construct(
        public string $institutionName,
        public ?string $degree,
        public ?string $fieldOfStudy,
        public ?string $startDate,
        public string $endDate,
    ) {
    }

    public function toArray(): array
    {
        return [
            'institutionName' => Str::lower($this->institutionName),
            'degree' => $this->degree ? Str::lower($this->degree) : $this->degree,
            'fieldOfStudy' => $this->fieldOfStudy ? Str::lower($this->fieldOfStudy) : $this->fieldOfStudy,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ];
    }
}