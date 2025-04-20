<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\ApiRequest;

use Illuminate\Validation\Rule;

class RegisterRequest extends ApiRequest
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
            'email' => [
                'required',
                'email',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('is_active', true)->whereNull('deleted_at');
                }),
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:30',
                'regex:' . config('regex.auth.password_format')
            ],
            'password_confirm' => 'required|same:password',
        ];
    }
}
