<?php

namespace App\Http\Requests\Test;

use App\Http\Requests\ApiRequest;

class ImmediateResultAPIRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'exam_session_token' => 'required|string'
        ];
    }
}