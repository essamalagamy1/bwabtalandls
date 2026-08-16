<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RolePermissionSeeder::class);
        
        User::factory()->create([
            'name' => 'superadmin',
            'email' => 'superadmin@admin.com',
            'password' => Hash::make('12345678'),
        ])->assignRole('admin')->givePermissionTo(Permission::all()->pluck('name')->toArray());
        
        $this->call([
            StageSeeder::class,
            GradeSeeder::class,
            SemesterSeeder::class,
            WeekSeeder::class,
            TrainingSeeder::class,
            ExamSeeder::class,
            QuestionSeeder::class,
            ExamAttemptSeeder::class,
            StudentAnswerSeeder::class,
            SiteSettingSeeder::class,
        ]);
    }
}
