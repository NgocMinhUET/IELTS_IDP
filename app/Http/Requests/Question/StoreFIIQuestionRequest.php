<?php

namespace App\Http\Requests\Question;

use App\Enum\AnswerType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFIIQuestionRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title' => 'required|string|max:255',
            'answers' => 'required|array',
            'answer_type' => ['required', Rule::in(AnswerType::values())],
        ];

        $answerType = $this->input('answer_type');
        if ($answerType == AnswerType::DRAG_DROP) {
            $rules['distractor_answers'] = 'nullable|array';
            $rules['distractor_answers.*'] = 'required|string';
            $rules['answer_label'] = 'null|string|max:255';
        }

        return $rules;
    }
}