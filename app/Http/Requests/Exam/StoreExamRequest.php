<?php

namespace App\Http\Requests\Exam;

use App\Enum\Models\SkillType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreExamRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'desc' => 'nullable|max:255',
            'skills' => 'required|array|' . Rule::in(array_column(SkillType::cases(), 'value')),
        ];
    }
}