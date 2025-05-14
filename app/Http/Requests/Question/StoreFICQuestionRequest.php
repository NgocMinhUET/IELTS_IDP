<?php

namespace App\Http\Requests\Question;

use App\Enum\AnswerType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFICQuestionRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'answers' => 'required|array',
            'answer_type' => ['required', Rule::in(AnswerType::values())],
            'placeholders' => 'required|array',
        ];

        $answerType = $this->input('answer_type');
        if ($answerType == AnswerType::DRAG_DROP->value) {
            $rules['distractor_answers'] = 'nullable|array';
            $rules['distractor_answers.*'] = 'required|string';
            $rules['answer_label'] = 'nullable|string|max:255';
        }

        return $rules;
    }
}