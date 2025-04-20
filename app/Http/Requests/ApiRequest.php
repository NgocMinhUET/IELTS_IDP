<?php

namespace App\Http\Requests;

use App\Common\ResponseApi;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Arr;

abstract class ApiRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Rule
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * Validate data
     *
     * @return array
     */
    public function validationData(): array
    {
        return parent::validationData();
    }

    /**
     * False validate
     *
     * @param Validator $validator
     * @return void
     */
    protected function failedValidation(Validator $validator): void
    {
        $failedList = $validator->errors()->toArray();

        foreach ($failedList as $key => $value) {
            $messageList[$key] = Arr::first($value);
        }

        throw new HttpResponseException(
            ResponseApi::validationError(
                [
                    "errors" => $failedList
                ],
                __('message.validation_error')
            )
        );
    }
}
