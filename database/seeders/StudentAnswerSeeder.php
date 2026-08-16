<?php

namespace Database\Seeders;

use App\Models\ExamAttempt;
use App\Models\StudentAnswer;
use Illuminate\Database\Seeder;

class StudentAnswerSeeder extends Seeder
{
    public function run(): void
    {
        $attempts = ExamAttempt::with('exam.questions')->get();
        
        foreach ($attempts as $attempt) {
            foreach ($attempt->exam->questions as $question) {
                // إجابة لكل سؤال في المحاولة
                $selectedOption = ['a', 'b', 'c', 'd'][rand(0, 3)];
                $isCorrect = $selectedOption === $question->correct_answer;
                
                StudentAnswer::create([
                    'exam_attempt_id' => $attempt->id,
                    'question_id' => $question->id,
                    'selected_option' => $selectedOption,
                    'is_correct' => $isCorrect,
                ]);
            }
        }
    }
}
