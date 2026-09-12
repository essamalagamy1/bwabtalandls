<?php

namespace App\Console\Commands;

use App\Models\AcademicEnrollment;
use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:migrate-student-enrollments')]
#[Description('Migrates current grade_id and section_id from users to the new academic_enrollments table.')]
class MigrateStudentEnrollments extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting migration of student enrollments...');

        $students = User::role('student')->whereNotNull('grade_id')->get();
        $count = 0;

        foreach ($students as $student) {
            // Check if they already have an enrollment for this grade
            $exists = AcademicEnrollment::where('user_id', $student->id)
                ->where('grade_id', $student->grade_id)
                ->where('is_current', true)
                ->exists();

            if (! $exists) {
                AcademicEnrollment::create([
                    'user_id' => $student->id,
                    'grade_id' => $student->grade_id,
                    'section_id' => $student->section_id,
                    'is_current' => true,
                    'notes' => 'Initial migration from users table',
                ]);
                $count++;
            }
        }

        $this->info("Successfully migrated $count student enrollments.");
    }
}
