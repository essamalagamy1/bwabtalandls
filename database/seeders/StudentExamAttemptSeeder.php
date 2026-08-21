<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\StudentAnswer;
use Carbon\Carbon;

class StudentExamAttemptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all active students with a grade assigned
        $students = User::role('student')
            ->whereNotNull('grade_id')
            ->where('status', 'active')
            ->get();

        foreach ($students as $student) {
            // Find active exams belonging to weeks -> semesters -> the student's grade
            $exams = Exam::whereHas('week.semester', function ($q) use ($student) {
                $q->where('grade_id', $student->grade_id);
            })
            ->where('is_active', true)
            ->inRandomOrder()
            ->take(rand(1, 3)) // Take 1 to 3 random exams
            ->get();

            foreach ($exams as $exam) {
                // Check if attempt already exists to prevent duplicate seeding
                if (ExamAttempt::where('user_id', $student->id)->where('exam_id', $exam->id)->exists()) {
                    continue;
                }

                $questions = $exam->questions;
                if ($questions->isEmpty()) {
                    continue; // Skip exams with no questions
                }

                // Simulate taking the exam sometime in the last 30 days
                $startedAt = Carbon::now()->subDays(rand(1, 30));
                $completedAt = clone $startedAt;
                $completedAt->addMinutes(rand(10, $exam->duration_minutes ?: 30));

                $attempt = ExamAttempt::create([
                    'exam_id' => $exam->id,
                    'user_id' => $student->id,
                    'total_score' => 0, // Will be calculated
                    'started_at' => $startedAt,
                    'completed_at' => $completedAt,
                ]);

                $totalScore = 0;
                $options = ['a', 'b', 'c', 'd'];

                foreach ($questions as $question) {
                    // 70% chance to get the answer correct
                    $isCorrect = rand(1, 100) <= 70;
                    
                    if ($isCorrect) {
                        $selectedOption = $question->correct_answer;
                    } else {
                        // Pick a random wrong option
                        $wrongOptions = array_filter($options, fn($opt) => $opt !== $question->correct_answer);
                        $selectedOption = !empty($wrongOptions) ? $wrongOptions[array_rand($wrongOptions)] : 'a';
                    }

                    StudentAnswer::create([
                        'exam_attempt_id' => $attempt->id,
                        'question_id' => $question->id,
                        'selected_option' => $selectedOption,
                        'is_correct' => $isCorrect,
                    ]);

                    if ($isCorrect) {
                        $totalScore += 1; // Assuming 1 point per correct answer
                    }
                }

                $status = $totalScore >= $exam->passing_score ? 'passed' : 'failed';

                // Update final score and status
                $attempt->update([
                    'total_score' => $totalScore,
                    'status' => $status
                ]);
            }
        }
    }
}
