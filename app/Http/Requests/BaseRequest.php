<?php

namespace App\Http\Requests;

use App\Common\ResponseApi;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseRequest extends FormRequest
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
}
