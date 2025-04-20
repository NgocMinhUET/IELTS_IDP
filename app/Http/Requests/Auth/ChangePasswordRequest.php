<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\ApiRequest;

class ChangePasswordRequest extends ApiRequest
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
            'old_password' => [
                'required',
                'string',
                'min:8',
                'max:30',
                'regex:' . config('regex.auth.password_format')
            ],
            'new_password' => [
                'required',
                'string',
                'min:8',
                'max:30',
                'regex:' . config('regex.auth.password_format')
            ],
            'confirm_password' => 'required|same:new_password',
        ];
    }
}
