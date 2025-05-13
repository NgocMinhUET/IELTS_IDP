<?php

namespace App\Http\Requests\SkillAnswer;

use App\Enum\QuestionTypeAPI;
use App\Http\Requests\ApiRequest;
use Illuminate\Validation\Rule;

class SubmitAnswerAPIRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'skill_session_token' => 'required|string',
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|string|max:255',
            'answers.*.answer' => 'required|string',
            'answers.*.question_type' => ['required', 'integer', Rule::in(QuestionTypeAPI::values())],
        ];
    }
}