<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResumeListRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'sort_order' => ['nullable', 'in:asc,desc'],
        ];
    }

    public function getPerPage(): int
    {
        return $this->input('per_page') ?? 15;
    }

    public function getSortOrder(): string
    {
        return $this->input('sort_order') ?? 'desc';
    }
}
