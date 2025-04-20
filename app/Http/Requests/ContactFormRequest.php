<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactFormRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Set to true if authorization is not required
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'string|max:20|regex:/^[0-9\-\+]{9,15}$/',
            'message' => 'required|string',
        ];
    }
}
