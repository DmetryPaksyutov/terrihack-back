<?php

namespace App\Services\Resume\DTO;

use Illuminate\Support\Str;

readonly class LanguageDto
{
    public function __construct(
        public string $language,
        public string $proficiency,
    ) {
    }

    public function toArray(): array
    {
        return [
            'language' => Str::lower($this->language),
            'proficiency' => Str::lower($this->proficiency),
        ];
    }
}