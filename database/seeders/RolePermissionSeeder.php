<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // roles
        $roles = ['student', 'admin'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role], ['is_main' => true]);
        }
        // //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // permissions

        $modules = [
            'role' => 'roles_mng',
            'admin' => 'admins_mng',
            'student' => 'students_mng',
            'stage' => 'stages_mng',
            'grade' => 'grades_mng',
            'semester' => 'semesters_mng',
            'week' => 'weeks_mng',
            'training' => 'trainings_mng',
            'exam' => 'exams_mng',
            'question' => 'questions_mng',
            'exam_attempt' => 'exam_attempts_mng',
            'student_answer' => 'student_answers_mng',
            'site_setting' => 'site_settings_mng',
        ];

        foreach ($modules as $module => $type) {
            foreach (['create', 'show', 'edit', 'delete'] as $action) {
                Permission::firstOrCreate(['name' => $action . '_' . $module], ['type' => $type]);
            }
        }
    }
}
