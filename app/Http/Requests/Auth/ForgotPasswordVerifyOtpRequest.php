<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\ApiRequest;

class ForgotPasswordVerifyOtpRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|max:255',
            'otp' => 'required|max:6',
        ];
    }
}
