<?php

namespace App\Observers;

use App\Actions\Media\GenerateRandomImageAction;
use App\Models\User;

class UserObserver
{
    public function created(User $user): void
    {
        $action = app(GenerateRandomImageAction::class);
        $action->execute($user);
    }
}
