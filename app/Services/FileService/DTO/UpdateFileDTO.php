<?php

declare(strict_types=1);

namespace App\Services\FileService\DTO;

use Illuminate\Support\Str;

readonly class UpdateFileDTO
{
    public function __construct(
        public ?string $pdfPath = null,
        public ?string $txtPath = null,
        public ?string $status = null,
        public ?string $statusText = null,
        public ?string $hash = null,
        public ?string $name = null,
    )
    {
    }

    public function getArray(): array
    {
        $attributes = get_object_vars($this);

        return collect($attributes)
            ->mapWithKeys(fn($value, $key) => [Str::snake($key) => $value])
            ->filter(fn($value) => !is_null($value))
            ->toArray();
    }
}
