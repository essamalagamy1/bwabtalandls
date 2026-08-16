<?php

namespace App\Http\Requests\Api;

class NotificationRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'notification_id' => 'required|exists:notifications,id',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
