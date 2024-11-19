<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DownloadPdfRequest extends FormRequest
{
    public function rules(): array
    {
        return [];
    }

    public function getId(): string
    {
        return $this->route('id');
    }
}
