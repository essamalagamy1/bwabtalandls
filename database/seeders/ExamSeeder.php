<?php

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\Week;
use Illuminate\Database\Seeder;

class ExamSeeder extends Seeder
{
    public function run(): void
    {
        $weeks = Week::all();
        
        foreach ($weeks as $week) {
            // اختبار واحد لكل أسبوع
            Exam::factory()->create([
                'week_id' => $week->id,
                'semester_id' => $week->semester_id,
            ]);
        }
    }
}
