<?php

namespace App\Actions\Api\Auth;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Str;

class LoginUserAction
{
    public function execute(array $data): array
    {
        $user = User::where('email', $data['email'])->first();

        abort_unless($user instanceof User, 404, __('lang.user_not_found'));

        // Single-device login: revoke all previous tokens
        $user->tokens()->delete();

        if (isset($data['fcm_token'])) {
            $user->update(['fcm_token' => $data['fcm_token']]);
        }

        return [
            'token' => $user->createToken(Str::random(50))->plainTextToken,
            'user' => new UserResource($user),
        ];
    }
}
