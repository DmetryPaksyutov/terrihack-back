<?php

namespace App\Http\Resources;

use App\Models\EmployeeResume;
use App\Services\Resume\DTO\LanguageDto;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin EmployeeResume|LanguageDto
 */
class LanguageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'language' => $this['language'],
            'proficiency' => $this['proficiency'],
        ];
    }
}
