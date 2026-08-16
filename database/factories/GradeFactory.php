<?php

namespace Database\Factories;

use App\Models\Stage;
use Illuminate\Database\Eloquent\Factories\Factory;

class GradeFactory extends Factory
{
    public function definition(): array
    {
        $grades = ['الصف الأول', 'الصف الثاني', 'الصف الثالث'];
        
        return [
            'stage_id' => Stage::factory(),
            'name' => $this->faker->randomElement($grades),
        ];
    }
}
