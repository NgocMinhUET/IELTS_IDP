<?php

namespace App\Http\Requests\Question;

use Illuminate\Foundation\Http\FormRequest;

class StoreSpeakingQuestionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'content' => ['required', 'string', 'max:100000'],
            'score' => 'required|numeric',
            'duration' => 'required|numeric',
        ];
    }
}