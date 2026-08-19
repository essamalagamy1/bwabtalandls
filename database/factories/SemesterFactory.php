<?php

namespace Database\Factories;

use App\Models\Grade;
use Illuminate\Database\Eloquent\Factories\Factory;

class SemesterFactory extends Factory
{
    public function definition(): array
    {
        $startYear = $this->faker->numberBetween(2017, 2026);
        $endYear = $startYear + 1;

        return [
            'grade_id' => Grade::factory(),
            'name' => $this->faker->randomElement(['الفصل الدراسي الأول', 'الفصل الدراسي الثاني', 'الفصل الدراسي الثالث']).' '.$startYear.' - '.$endYear,
            'is_active' => $this->faker->boolean(80),
            'start_date' => $this->faker->dateTimeBetween('-1 month', '+1 month')->format('Y-m-d'),
            'end_date' => $this->faker->dateTimeBetween('+2 months', '+5 months')->format('Y-m-d'),
        ];
    }
}
