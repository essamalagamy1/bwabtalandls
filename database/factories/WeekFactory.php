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
        ];
    }
}
