<?php

namespace App\Http\Requests\Part;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePartRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'desc' => ['nullable', 'string', 'max:2048'],
        ];
    }
}