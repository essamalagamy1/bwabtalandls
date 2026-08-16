<?php

namespace Database\Factories;

use App\Models\Exam;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExamAttemptFactory extends Factory
{
    public function definition(): array
    {
        return [
            'exam_id' => Exam::factory(),
            'user_id' => User::factory(),
            'total_score' => $this->faker->numberBetween(0, 100),
            'status' => $this->faker->randomElement(['passed', 'failed']),
            'started_at' => $this->faker->dateTimeBetween('-1 week', 'now'),
            'completed_at' => function (array $attributes) {
                // وقت الانتهاء بعد وقت البدء
                return \Carbon\Carbon::parse($attributes['started_at'])->addMinutes($this->faker->numberBetween(10, 60));
            },
        ];
    }
}
