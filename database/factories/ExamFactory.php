<?php

namespace Database\Factories;

use App\Models\Semester;
use App\Models\Week;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExamFactory extends Factory
{
    public function definition(): array
    {
        return [
            'week_id' => Week::factory(),
            'semester_id' => Semester::factory(),
            'title' => 'اختبار الأسبوع ' . $this->faker->numberBetween(1, 10),
            'description' => 'الرجاء قراءة الأسئلة بعناية قبل الإجابة',
            'duration_minutes' => $this->faker->randomElement([30, 45, 60, 90]),
            'passing_score' => $this->faker->numberBetween(50, 70),
            'assignment_date' => $this->faker->dateTimeBetween('-1 week', '+1 week'),
        ];
    }
}
