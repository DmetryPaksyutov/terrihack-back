<?php

namespace App\Http\Resources;

use App\Models\EmployeeResume;
use App\Services\Resume\DTO\WorkExperienceDto;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin EmployeeResume|WorkExperienceDto
 */
class WorkExperienceResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'companyName' => $this['companyName'],
            'position' => $this['position'],
            'startDate' => $this['startDate'],
            'endDate' => $this['endDate'],
        ];
    }
}
