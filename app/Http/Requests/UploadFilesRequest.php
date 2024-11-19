<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadFilesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'pdf_files.*' => ['required', 'file', 'mimes:pdf'],
            'pdf_files' => 'required', 'array', 'max:10',
        ];
    }
}
