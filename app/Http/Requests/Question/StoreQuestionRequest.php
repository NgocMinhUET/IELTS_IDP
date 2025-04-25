<?php

namespace App\Http\Requests\Question;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreQuestionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
//            'type' => 'required', Rule::in(['choice']),
            'choice_sub_questions' => 'required|array',
            'title' => 'required|string|max:255',
        ];
    }
}