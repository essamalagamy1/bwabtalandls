<?php

namespace Database\Seeders;

use App\Models\Semester;
use App\Models\Week;
use Illuminate\Database\Seeder;

class WeekSeeder extends Seeder
{
    public function run(): void
    {
        $semesters = Semester::all();
        $weeks = ['الأسبوع الأول', 'الأسبوع الثاني', 'الأسبوع الثالث', 'الأسبوع الرابع'];
        
        foreach ($semesters as $semester) {
            foreach ($weeks as $index => $weekTitle) {
                Week::create([
                    'semester_id' => $semester->id,
                    'title' => $weekTitle,
                    'order' => $index + 1,
                ]);
            }
        }
    }
}
