<?php

namespace Database\Seeders;

use App\Models\Stage;
use Illuminate\Database\Seeder;

class StageSeeder extends Seeder
{
    public function run(): void
    {
        $stages = [ 'المرحلة المتوسطة', 'المرحلة الثانوية'];
        foreach ($stages as $stage) {
            Stage::create(['name' => $stage]);
        }
    }
}
