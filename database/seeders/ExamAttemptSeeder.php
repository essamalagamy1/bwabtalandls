<?php

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\User;
use Illuminate\Database\Seeder;

class ExamAttemptSeeder extends Seeder
{
    public function run(): void
    {
        // افترض أن لدينا طلاب فقط
        $students = User::whereHas('roles', function($q) {
            $q->where('name', 'student');
        })->get();

        if ($students->count() === 0) {
            // No students available to take exams.
            return;
        }

        $exams = Exam::all();
        
        foreach ($students as $student) {
            foreach ($exams->take(3) as $exam) {
                // محاولة لكل طالب في 3 اختبارات
                ExamAttempt::factory()->create([
                    'exam_id' => $exam->id,
                    'user_id' => $student->id,
                ]);
            }
        }
    }
}
