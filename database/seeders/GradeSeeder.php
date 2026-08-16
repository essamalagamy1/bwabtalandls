<?php

namespace Database\Seeders;

use App\Models\Grade;
use App\Models\Stage;
use Illuminate\Database\Seeder;

class GradeSeeder extends Seeder
{
    public function run(): void
    {
        $stages = Stage::all();
        $grades = ['الصف الأول', 'الصف الثاني', 'الصف الثالث'];
        
        foreach ($stages as $stage) {
            foreach ($grades as $gradeName) {
                Grade::create([
                    'stage_id' => $stage->id,
                    'name' => $gradeName,
                ]);
            }
        }
    }
}
