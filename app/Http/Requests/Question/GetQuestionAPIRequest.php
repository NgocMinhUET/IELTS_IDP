<?php

namespace App\Http\Requests\Question;

use App\Http\Requests\ApiRequest;

class GetQuestionAPIRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'target_id' => 'required|integer|exists:skills,id',
            'exam_session_token' => 'required|string'
        ];
    }
}