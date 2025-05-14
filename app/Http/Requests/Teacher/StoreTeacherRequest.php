<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeacherRequest extends FormRequest
{
//    public function authorize()
//    {
//        return true; // Set to true if authorization is not required
//    }

    public function rules(): array
    {
        $isUpdate = $this->method() === 'PUT';
        $emailRule = $isUpdate ? 'required|email|unique:admins,email,' . $this->id :
            'required|email|unique:admins,email';

        $rules = [
            'email' => $emailRule,
            'name' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'is_active' => 'required|in:0,1',
        ];

        if ($isUpdate) {
            unset($rules['password']);
            $rules['new_password'] = ['nullable', 'string', 'max:255'];
        }

        return $rules;
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'email' => strtolower($this->email)
        ]);
    }
}