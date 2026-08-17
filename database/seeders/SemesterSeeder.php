<?php

namespace Database\Seeders;

use App\Models\Grade;
use App\Models\Semester;
use Illuminate\Database\Seeder;

class SemesterSeeder extends Seeder
{
    public function run(): void
    {
        $grades = Grade::all();
        $semesters = ['الفصل الدراسي الأول', 'الفصل الدراسي الثاني'];
        
        foreach ($grades as $grade) {
            foreach ($semesters as $index => $semesterName) {
                Semester::create([
                    'grade_id' => $grade->id,
                    'name' => $semesterName,
                    'is_active' => $index === 0, // الاول هو النشط
                    'start_date' => $index === 0 ? now()->format('Y-m-d') : now()->addMonths(4)->format('Y-m-d'),
                    'end_date' => $index === 0 ? now()->addMonths(3)->format('Y-m-d') : now()->addMonths(7)->format('Y-m-d'),
                ]);
            }
        }
    }
}
