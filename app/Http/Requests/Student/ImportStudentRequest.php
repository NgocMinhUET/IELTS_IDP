<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class ImportStudentRequest extends FormRequest
{
//    public function authorize()
//    {
//        return true; // Set to true if authorization is not required
//    }

    public function rules(): array
    {
        $excelCsvMIME = 'text/csv,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';

        return [
            'file' => "required|mimes:xlsx,xls,csv|mimetypes:{$excelCsvMIME}|max:51200" // 50MB
        ];
    }
}