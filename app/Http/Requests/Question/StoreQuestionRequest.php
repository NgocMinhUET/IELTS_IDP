<?php

namespace App\Http\Requests\Question;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreQuestionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:2048',
            'choice_sub_questions' => 'required|array',
            'choice_sub_questions.*.question' => 'required|string|max:2048',
            'choice_sub_questions.*.min_option' => 'required|numeric',
            'choice_sub_questions.*.max_option' => 'required|numeric',
            'choice_sub_questions.*.choice_options' => 'required|array',
            'choice_sub_questions.*.choice_options.*.answer' => 'required|string|max:2048',
        ];
    }
}