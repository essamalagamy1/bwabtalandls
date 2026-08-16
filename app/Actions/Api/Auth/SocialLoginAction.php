<?php

namespace App\Actions\Api\Auth;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SocialLoginAction
{
    public function execute(array $data): array
    {
        $user = DB::transaction(function () use ($data) {
            return $this->findOrCreateUser($data);
        });

        // Single-device login: revoke all previous tokens
        $user->tokens()->delete();

        return [
            'token' => $user->createToken(Str::random(50))->plainTextToken,
            'user' => new UserResource($user),
        ];
    }

    private function findOrCreateUser(array $data): User
    {
        // 1. Try to find by provider + provider_id
        $user = User::where('provider', $data['provider'])
            ->where('provider_id', $data['provider_id'])
            ->first();

        if ($user) {
            $user->update([
                'provider_token' => $data['provider_token'],
                'fcm_token' => $data['fcm_token'],
            ]);

            return $user;
        }

        // 2. Try to find by email
        $user = User::where('email', $data['email'])->first();

        if ($user) {
            $user->update([
                'provider' => $data['provider'],
                'provider_id' => $data['provider_id'],
                'provider_token' => $data['provider_token'],
                'email_verified_at' => now(),
                'fcm_token' => $data['fcm_token'],
            ]);

            return $user;
        }

        // 3. Create new user
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'provider' => $data['provider'],
            'provider_id' => $data['provider_id'],
            'provider_token' => $data['provider_token'],
            'email_verified_at' => now(),
            'password' => bcrypt(Str::random(32)),
            'fcm_token' => $data['fcm_token'],
        ]);

        $user->assignRole('user');

        return $user;
    }
}
