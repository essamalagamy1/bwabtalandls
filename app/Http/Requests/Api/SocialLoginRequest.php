<?php

namespace App\Http\Requests\Api;

class SocialLoginRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'provider' => ['required', 'string', 'in:google,apple'],
            'provider_id' => ['required', 'string'],
            'provider_token' => ['required', 'string'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'fcm_token' => ['required', 'string', 'max:255'],
        ];
    }
}
