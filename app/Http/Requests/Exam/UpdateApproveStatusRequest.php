<?php

namespace App\Http\Requests\Exam;

use App\Enum\Models\ApproveStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateApproveStatusRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(array_keys(ApproveStatus::assoc()))],
        ];
    }
}