<?php

namespace App\Http\Requests\Test;

use Illuminate\Foundation\Http\FormRequest;

class StoreTestRequest extends FormRequest
{
//    public function authorize()
//    {
//        return true; // Set to true if authorization is not required
//    }

    public function rules(): array
    {
        return [
            'desc' => 'nullable|max:255',
            'start_time' => 'nullable|date|date_format:Y-m-d H:i',
            'end_time' => 'nullable|date|date_format:Y-m-d H:i|after:start_time',
            'exams' => 'required|array',
            'exams.*' => 'integer|exists:exams,id',
        ];
    }
}