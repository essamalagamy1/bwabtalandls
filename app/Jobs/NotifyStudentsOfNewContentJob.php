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
        public string $contentType, // 'exam' or 'training'
        public ?string $description = null,
        public array $extraDetails = []
    ) {}

    public function handle(): void
    {
        $students = User::role('student')
            ->where('status', 'active')
            ->where('grade_id', $this->gradeId)
            ->get();

        if ($students->isNotEmpty()) {
            $notificationTitle = $this->contentType === 'exam' 
                ? 'اختبار جديد متاح: ' . $this->title
                : 'تدريب جديد متاح: ' . $this->title;
            
            $bodyLines = [];
            if ($this->contentType === 'exam') {
                $bodyLines[] = "تمت إضافة وتفعيل اختبار جديد بعنوان: {$this->title}";
            } else {
                $bodyLines[] = "تمت إضافة وتفعيل تدريب جديد بعنوان: {$this->title}";
            }

            if ($this->description) {
                $bodyLines[] = "الوصف: {$this->description}";
            }

            foreach ($this->extraDetails as $label => $value) {
                if ($value !== null && $value !== '') {
                    $bodyLines[] = "{$label}: {$value}";
                }
            }

            $notificationBody = implode("\n", $bodyLines);
            $url = $this->contentType === 'exam' ? route('student.exams') : route('student.trainings');

            Notification::send($students, new UserNotification(
                $notificationTitle,
                $notificationBody,
                $url
            ));
        }
    }
}
