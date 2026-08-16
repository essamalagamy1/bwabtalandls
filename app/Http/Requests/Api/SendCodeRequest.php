<?php

namespace App\Http\Requests\Api;

class SendCodeRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'email' => ['bail', 'required', 'email:dns', 'exists:users,email'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
