<?php

namespace App\Actions\Api\Auth;

use App\Models\User;
use App\Notifications\SendCodeNotification;

class SendCodeAction
{
    public function execute(array $data): void
    {
        $user = User::where('email', $data['email'])->first();

        abort_unless($user instanceof User, 404, __('lang.user_not_found'));

        $user->notify(new SendCodeNotification($user, randomOtpCode()));
    }
}
