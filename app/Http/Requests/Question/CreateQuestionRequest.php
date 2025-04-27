<?php

namespace App\Http\Requests\Question;

use App\Enum\QuestionType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateQuestionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'type' => ['required', Rule::in(QuestionType::values())],
        ];
    }
}