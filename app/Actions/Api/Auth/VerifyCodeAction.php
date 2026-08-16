<?php

namespace App\Actions\Api\Auth;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VerifyCodeAction
{
    public function execute(array $data): array
    {
        $user = User::where('email', $data['email'])->first();

        abort_unless($user instanceof User, 404, __('lang.user_not_found'));

        DB::transaction(function () use ($user) {
            $user->update([
                'verification_code' => null,
                'email_verified_at' => now(),
            ]);
        });

        // Single-device login: revoke all previous tokens
        $user->tokens()->delete();

        return [
            'token' => $user->createToken(Str::random(50))->plainTextToken,
            'user' => new UserResource($user),
        ];
    }
}
