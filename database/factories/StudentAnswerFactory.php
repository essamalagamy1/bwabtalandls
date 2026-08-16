<?php

namespace Database\Factories;

use App\Models\ExamAttempt;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentAnswerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'exam_attempt_id' => ExamAttempt::factory(),
            'question_id' => Question::factory(),
            'selected_option' => $this->faker->randomElement(['a', 'b', 'c', 'd']),
            'is_correct' => $this->faker->boolean(60),
        ];
    }
}
