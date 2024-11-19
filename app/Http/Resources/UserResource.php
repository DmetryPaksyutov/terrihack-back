<?php

namespace App\Http\Resources;

use App\Models\EmployeeResume;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

/**
 * @mixin EmployeeResume
 */
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'firstName' => Str::ucfirst($this->first_name ?? '') ?: null,
            'lastName' => Str::ucfirst($this->last_name ?? '') ?: null,
            'patronymic' => Str::ucfirst($this->patronymic ?? '') ?: null,
            'dateOfBirth' => $this->date_of_birth,
            'position' => Str::ucfirst($this->position ?? '') ?: null,
            'citizenship' => Str::ucfirst($this->citizenship ?? '') ?: null,
            'country' => Str::ucfirst($this->country ?? '') ?: null,
            'city' => Str::ucfirst($this->city ?? '') ?: null,
            'sex' => $this->sex,
            'expectedSalary' => $this->expected_salary,
            'expectedSalaryCurrency' => $this->expected_salary_currency,
            'contactInfo' => [
                'phone' => $this->phone,
                'email' => $this->email,
                'linkedin' => $this->linkedin,
                'telegram' => $this->telegram,
            ],
        ];
    }
}
