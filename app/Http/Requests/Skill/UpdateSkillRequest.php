<?php

namespace App\Http\Requests\Skill;

use App\Enum\Models\SkillType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSkillRequest extends FormRequest
{
//    public function authorize()
//    {
//        return true; // Set to true if authorization is not required
//    }

    public function rules(): array
    {
        return [
            'desc' => 'nullable|max:255',
            'duration' => 'required|numeric',
            'bonus_time' => 'nullable|numeric',
            'parts' => 'required|array',
            'parts.*.title' => 'required|max:255',
            'audio' => 'nullable|mimes:mp3,wav|mimetypes:audio/mpeg,audio/wav|max:102400',
        ];
    }
}