<?php

namespace Database\Factories;

use App\Models\Grade;
use Illuminate\Database\Eloquent\Factories\Factory;

class SemesterFactory extends Factory
{
    public function definition(): array
    {
        return [
            'grade_id' => Grade::factory(),
            'name' => $this->faker->randomElement(['الفصل الدراسي الأول', 'الفصل الدراسي الثاني', 'الفصل الدراسي الثالث']),
            'is_active' => $this->faker->boolean(50),
        ];
    }
}
