<?php

namespace App\Jobs;

use App\Mail\AccountStatusNotification;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ActivatePendingStudentsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @param array<int>|null $studentIds Specific student IDs to activate, or null for all pending students.
     */
    public function __construct(public ?array $studentIds = null) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $query = User::role('student')->where('status', 'pending');

        if (! empty($this->studentIds)) {
            $query->whereIn('id', $this->studentIds);
        }

        $pendingStudents = $query->get();

        if ($pendingStudents->isEmpty()) {
            Log::info('ActivatePendingStudentsJob: No pending students found to activate.');

            return;
        }

        $activatedCount = 0;

        foreach ($pendingStudents as $student) {
            try {
                $student->update(['status' => 'active']);

                if (! empty($student->email)) {
                    Mail::to($student->email)->send(new AccountStatusNotification($student, 'active'));
                }

                $activatedCount++;
            } catch (\Throwable $e) {
                Log::error("ActivatePendingStudentsJob: Failed to activate or notify student ID {$student->id} ({$student->email}): " . $e->getMessage());
            }
        }

        Log::info("ActivatePendingStudentsJob: Successfully activated {$activatedCount} pending student(s).");
    }
}
