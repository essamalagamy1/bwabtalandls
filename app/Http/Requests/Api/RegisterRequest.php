<?php

namespace App\Http\Requests\Api;

use App\Models\User;
use Illuminate\Validation\Validator;

class RegisterRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|same:password',
            'phone_key' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255|unique:users,phone->number',
            'fcm_token' => 'nullable|string|max:255',
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                if (User::where('email', $this->email)->whereNotNull('email_verified_at')->exists()) {
                    $validator->errors()->add('email', __('lang.email_already_exists'));
                }
            },
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
