<?php

namespace Database\Seeders;

use App\Models\Training;
use App\Models\Week;
use Illuminate\Database\Seeder;

class TrainingSeeder extends Seeder
{
    public function run(): void
    {
        $weeks = Week::all();
        
        foreach ($weeks as $week) {
            // كل أسبوع نضع له تدريبين
            Training::factory()->count(2)->create([
                'week_id' => $week->id,
                'semester_id' => $week->semester_id,
            ]);
        }
    }
}
