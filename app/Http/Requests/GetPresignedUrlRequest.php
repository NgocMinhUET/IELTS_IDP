<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;

class GetPresignedUrlRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'filename' => 'required|string|max:255',
            'prefix' => 'required|string|in:teams,messages',
        ];
    }
}
