<?php

namespace App\Http\Resources;

use App\Models\EmployeeResume;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin EmployeeResume
 */
class EmployeeResumeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'resumeId' => $this->resume_id,
            'user' => UserResource::make($this),
            'education' => EducationResource::make(collect($this->education)->first()),
            'workExperience' => WorkExperienceResource::make(collect($this->work_experience)->first()),
            'isContinuousWorkExperience' => $this->is_work_experience_continuous,
            'workExperienceInMonths' => $this->work_experience_in_months,
            'skills' => collect($this->skills)->filter()->values(),
            'languages' => LanguageResource::collection($this->languages),
            'personalQualities' => collect($this->personal_qualities)->filter()->values(),
            'resumeUpdatedAt' => $this->resume_updated_at,
        ];
    }
}
