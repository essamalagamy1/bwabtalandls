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

class NotifyStudentsOfNewContentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $gradeId,
        public string $title,
        public string $contentType // 'exam' or 'training'
    ) {}

    public function handle(): void
    {
        $students = User::role('student')
            ->where('status', 'active')
            ->where('grade_id', $this->gradeId)
            ->get();

        if ($students->isNotEmpty()) {
            $notificationTitle = $this->contentType === 'exam' 
                ? 'اختبار جديد متاح' 
                : 'تدريب جديد متاح';
            
            $notificationBody = $this->contentType === 'exam'
                ? "تمت إضافة اختبار جديد بعنوان: {$this->title}"
                : "تمت إضافة تدريب جديد بعنوان: {$this->title}";

            // The URL could direct them to their dashboard or the specific section
            $url = route('dashboard');

            Notification::send($students, new UserNotification(
                $notificationTitle,
                $notificationBody,
                $url
            ));
        }
    }
}
