<?php

namespace App\Livewire\Student;

use App\Models\Exam;
use App\Models\Training;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class StudentDashboard extends Component
{
    public array $progressChart = [];

    public function placeholder(): View
    {
        return view('livewire.placeholders.page-loading');
    }

    public function render(): View
    {
        $user = Auth::user();
        $user->load('grade.stage');
        $gradeId = $user->grade_id;

        $activeSemester = \App\Models\Semester::where('grade_id', $gradeId)
            ->where('is_active', true)
            ->first();

        $exams = Exam::whereHas('week', function ($query) use ($gradeId) {
            $query->whereHas('semester', function ($q) use ($gradeId) {
                $q->where('grade_id', $gradeId)->where('is_active', true);
            });
        })
        ->with(['week.semester', 'attempts' => function($q) use ($user) {
            $q->where('user_id', $user->id);
        }])
        ->withCount('questions')
        ->latest()
        ->get();

        $trainings = Training::whereHas('week', function ($query) use ($gradeId) {
            $query->whereHas('semester', function ($q) use ($gradeId) {
                $q->where('grade_id', $gradeId)->where('is_active', true);
            });
        })
        ->with('week.semester')
        ->where('is_published', true)
        ->latest()
        ->get();

        // Analytics
        $allAttempts = \App\Models\ExamAttempt::where('user_id', $user->id)->orderBy('created_at')->get();
        
        $totalExamsTaken = $allAttempts->count();
        $averageScore = $totalExamsTaken > 0 ? $allAttempts->avg('total_score') : 0;
        
        // Progress Chart Data
        $this->progressChart = [
            'type' => 'line',
            'data' => [
                'labels' => $allAttempts->map(fn($a) => $a->created_at->format('M d'))->toArray(),
                'datasets' => [
                    [
                        'label' => __('lang.score') ?? 'الدرجة',
                        'data' => $allAttempts->pluck('total_score')->toArray(),
                        'borderColor' => '#25376F',
                        'tension' => 0.4
                    ]
                ]
            ]
        ];

        return view('livewire.student.student-dashboard', compact('user', 'activeSemester', 'exams', 'trainings', 'totalExamsTaken', 'averageScore'));
    }
}
