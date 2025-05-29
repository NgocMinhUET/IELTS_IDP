<?php

namespace App\Http\Requests\SkillAnswer;

use App\Http\Requests\ApiRequest;

class GetSpeakingPresignedUrlAPIRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'skill_session_token' => 'required|string',
            'question_id' => 'required|string',
        ];
    }
}