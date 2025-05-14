<?php

namespace App\Http\Requests\Test;

use App\Http\Requests\ApiRequest;

class EnrollTestAPIRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'test_id' => 'required|integer|exists:tests,id'
        ];
    }
}