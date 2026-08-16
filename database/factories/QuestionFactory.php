<?php

namespace Database\Factories;

use App\Models\Exam;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'exam_id' => Exam::factory(),
            'question_text' => 'ما هو الجواب الصحيح للسؤال رقم ' . $this->faker->numberBetween(1, 100) . '؟',
            'option_a' => 'الخيار الأول: ' . $this->faker->word,
            'option_b' => 'الخيار الثاني: ' . $this->faker->word,
            'option_c' => 'الخيار الثالث: ' . $this->faker->word,
            'option_d' => 'الخيار الرابع: ' . $this->faker->word,
            'correct_answer' => $this->faker->randomElement(['a', 'b', 'c', 'd']),
        ];
    }
}
