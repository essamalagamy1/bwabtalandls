<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;

abstract class ApiRequest extends FormRequest
{
    protected function failedValidation(Validator $validator): void
    {
        throw new ValidationException($validator, Response::validationError($validator));
    }
}
