<?php

namespace Database\Factories;

use App\Models\Semester;
use Illuminate\Database\Eloquent\Factories\Factory;

class WeekFactory extends Factory
{
    public function definition(): array
    {
        return [
            'semester_id' => Semester::factory(),
            'title' => 'الأسبوع ' . $this->faker->numberBetween(1, 15),
            'order' => $this->faker->numberBetween(1, 15),
            'is_active' => $this->faker->boolean(80),
            'start_date' => $this->faker->dateTimeBetween('-1 week', '+1 week')->format('Y-m-d'),
            'end_date' => $this->faker->dateTimeBetween('+2 weeks', '+4 weeks')->format('Y-m-d'),
        ];
    }
}
