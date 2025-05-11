<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
//    public function authorize()
//    {
//        return true; // Set to true if authorization is not required
//    }

    public function rules(): array
    {
        $isUpdate = $this->method() === 'PUT';
        $emailRule = $isUpdate ? 'required|email|unique:users,email,' . $this->id :
            'required|email|unique:users,email';

        $rules = [
            'email' => $emailRule,
            'name' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'is_active' => 'required|in:0,1',
            'search_prefix' => 'nullable|string|max:255',
            'code' => 'required|string|max:255',
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