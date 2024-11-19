<?php

namespace App\Http\Resources;

use App\Models\EmployeeResume;
use App\Services\Resume\DTO\EducationDto;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

/**
 * @mixin EmployeeResume|EducationDto
 */
class EducationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'institutionName' => Str::ucfirst($this['institutionName'] ?? '') ?: null,
            'degree' => $this['degree'],
            'fieldOfStudy' => $this['fieldOfStudy'],
            'startDate' => $this['startDate'],
            'endDate' => $this['endDate'],
        ];
    }
}
