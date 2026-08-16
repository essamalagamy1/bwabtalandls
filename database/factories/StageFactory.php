<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StageFactory extends Factory
{
    public function definition(): array
    {
        $stages = ['المرحلة الابتدائية', 'المرحلة المتوسطة', 'المرحلة الثانوية', 'المرحلة الجامعية'];
        
        return [
            'name' => $this->faker->unique()->randomElement($stages),
        ];
    }
}
