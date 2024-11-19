<?php

namespace App\Services\Resume\DTO;

use Illuminate\Support\Carbon;

readonly class WorkExperienceDto
{
    public function __construct(
        public string $companyName,
        public string $position,
        public string $startDate,
        public string $endDate,
    ) {
    }

    public function toArray(): array
    {
        return [
            'companyName' => $this->companyName,
            'position' => $this->position,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ];
    }
}