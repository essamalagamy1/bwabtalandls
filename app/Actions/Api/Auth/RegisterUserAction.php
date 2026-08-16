<?php

namespace App\Actions\Api\Auth;

use App\Models\User;
use App\Notifications\SendCodeNotification;
use App\Services\PhoneService;
use Illuminate\Support\Facades\DB;

class RegisterUserAction
{
    public function execute(array $data): void
    {
        DB::transaction(function () use ($data) {
            $user = User::updateOrCreate(['email' => $data['email'], 'email_verified_at' => null],
                [
                    'name' => $data['name'],
                    'password' => $data['password'],
                    'email' => $data['email'],
                    'phone' => isset($data['phone']) ? PhoneService::formatNumber($data['phone']) : null,
                    'phone_key' => $data['phone_key'] ?? null,
                    'fcm_token' => $data['fcm_token'] ?? null,
                ]
            );
            $user->assignRole('user');
            $user->notify(new SendCodeNotification($user, randomOtpCode()));
        });
    }
}
