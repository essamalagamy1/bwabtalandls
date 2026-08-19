<?php

namespace Database\Factories;

use App\Models\Semester;
use App\Models\Week;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrainingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'week_id' => Week::factory(),
            'semester_id' => Semester::factory(),
            'title' => 'تدريب على الدرس ' . $this->faker->numberBetween(1, 10),
            'description' => $this->faker->realText(200),
            'type' => $this->faker->randomElement(['video', 'pdf', 'file', 'link']),
            'url' => $this->faker->url,
            'is_active' => $this->faker->boolean(80),
        ];
    }
}
