<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AiSearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'q' => [
                'required',
                'string',
                'min:5',
                'max:150',
            ]
        ];
    }
}
