<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\UserNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class NotifyAdminsOfNewStudentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public User $user) {}

    public function handle(): void
    {
        $admins = User::permission('edit_student')->get();
        if ($admins->isNotEmpty()) {
            Notification::send($admins, new UserNotification(
                'طالب جديد بانتظار المراجعة',
                'تم تسجيل الطالب '.$this->user->name.' وهو بانتظار تفعيل حسابه.',
                route('students', ['search_name' => $this->user->name])
            ));
        }
    }
}
