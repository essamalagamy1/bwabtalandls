<?php

namespace App\Http\Requests\Api;

class ProfileUpdateRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:4048',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
