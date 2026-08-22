<?php

use App\Http\Controllers\LanguageController;
use App\Livewire\Dashboard\Admin\AdminData;
use App\Livewire\Dashboard\Dashboard;
use App\Livewire\Dashboard\Exam\ExamData;
use App\Livewire\Dashboard\ExamAttempt\ExamAttemptData;
use App\Livewire\Dashboard\Grade\GradeData;
use App\Livewire\Dashboard\Profile\Profile;
use App\Livewire\Dashboard\Question\QuestionData;
use App\Livewire\Dashboard\Reports\ExamReports;
use App\Livewire\Dashboard\Reports\StudentReports;
use App\Livewire\Dashboard\Role\CreateRole;
use App\Livewire\Dashboard\Role\RoleData;
use App\Livewire\Dashboard\Role\UpdateRole;
use App\Livewire\Dashboard\Section\SectionData;
use App\Livewire\Dashboard\Semester\SemesterData;
use App\Livewire\Dashboard\SiteSetting\UpdateSiteSetting;
use App\Livewire\Dashboard\Stage\StageData;
use App\Livewire\Dashboard\Student\StudentData;
use App\Livewire\Dashboard\Training\TrainingData;
use App\Livewire\Dashboard\Week\WeekData;
use App\Livewire\Student\ExamResult;
use App\Livewire\Student\StudentExamsData;
use App\Livewire\Student\TakeExam;
use App\Http\Controllers\NotificationManagerController;
use Illuminate\Support\Facades\Route;

// getFirstMediaUrl('image')
Route::middleware(['web-language'])->group(function () {
    Route::get('web-language/{lang}', LanguageController::class)->name('web-language');
    Route::redirect('/', 'login')->name('home');
    // authentication routes
    Route::middleware(['auth', 'verified', 'role:admin|student'])->group(function () {
        Route::livewire('profile', Profile::class)->name('profile'); // profile
        Route::livewire('dashboard', Dashboard::class)->name('dashboard'); // dashboard
        // roles
        Route::prefix('roles')->middleware('permission:show_role')->group(function () {
            Route::livewire('/', RoleData::class)->name('roles');
            Route::livewire('/create', CreateRole::class)->name('roles.create')->middleware('permission:create_role');
            Route::livewire('/{role}/edit', UpdateRole::class)->name('roles.edit')->middleware('permission:edit_role');
        });
        Route::livewire('students', StudentData::class)->name('students')->middleware('permission:show_student');
        Route::livewire('students/{user}/profile', \App\Livewire\Dashboard\Student\StudentProfile::class)->name('students.profile')->middleware('permission:show_student');
        Route::livewire('admins', AdminData::class)->name('admins')->middleware('permission:show_admin'); // users
        Route::livewire('stages', StageData::class)->name('stages')->middleware('permission:show_stage');
        Route::livewire('grades', GradeData::class)->name('grades')->middleware('permission:show_grade');
        Route::livewire('sections', SectionData::class)->name('sections')->middleware('permission:show_section');
        Route::livewire('semesters', SemesterData::class)->name('semesters')->middleware('permission:show_semester');
        Route::livewire('weeks', WeekData::class)->name('weeks')->middleware('permission:show_week');
        Route::livewire('trainings', TrainingData::class)->name('trainings')->middleware('permission:show_training');
        Route::livewire('exams', ExamData::class)->name('exams')->middleware('permission:show_exam');
        Route::livewire('exams/attempts', ExamAttemptData::class)->name('exam_attempts')->middleware('permission:show_exam_attempt');
        Route::livewire('questions', QuestionData::class)->name('questions')->middleware('permission:show_question');
        Route::livewire('reports/students', StudentReports::class)->name('reports.students')->middleware('permission:show_student_report');
        Route::livewire('reports/exams', ExamReports::class)->name('reports.exams')->middleware('permission:show_exam_report');
        Route::livewire('site-settings', UpdateSiteSetting::class)->name('site-settings')->middleware('permission:show_site_setting'); // site settings
        
        // Push notification subscription
        Route::post('/save-subscription', NotificationManagerController::class)->name('save-subscription');
    });

    // student routes
    Route::middleware(['auth', 'verified', 'role:student'])->group(function () {
        Route::livewire('student/exams', StudentExamsData::class)->name('student.exams');
        Route::livewire('student/exams/{exam}/take', TakeExam::class)->name('student.exams.take');
        Route::livewire('student/exams/{exam}/result', ExamResult::class)->name('student.exams.result');
        Route::livewire('student/trainings', \App\Livewire\Student\StudentTrainingsData::class)->name('student.trainings');
    });

    // guest routes
    require __DIR__.'/auth.php';
});
