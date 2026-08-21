<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Grade;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Only seed students if there are none
        if (User::role('student')->count() === 0) {
            $grades = Grade::pluck('id');
            User::factory()->count(15)->create()->each(function ($user) use ($grades) {
                $user->assignRole('student');
                $status = ['active', 'active', 'pending', 'inactive'][rand(0, 3)]; // Higher chance of active
                $updateData = ['status' => $status];
                
                if ($grades->isNotEmpty()) {
                    $updateData['grade_id'] = $grades->random();
                }
                
                $user->update($updateData);
            });
        }
    }
}
