<?php

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\Question;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $exams = Exam::all();
        
        foreach ($exams as $exam) {
            // 5 أسئلة لكل اختبار
            Question::factory()->count(5)->create([
                'exam_id' => $exam->id,
            ]);
        }
    }
}
