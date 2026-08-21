<?php

namespace App\Livewire\Dashboard\Student;

use App\Models\User;
use App\Models\ExamAttempt;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('student_profile')]
#[Lazy]
class StudentProfile extends Component
{
    public User $user;
    
    public array $progressChart = [];
    public array $statusChart = [];

    public function placeholder(): View
    {
        return view('livewire.placeholders.page-loading');
    }

    public function mount(User $user): void
    {
        $this->authorize('show_student');
        
        // Ensure the user is a student
        if (!$user->hasRole('student')) {
            abort(404, 'User is not a student.');
        }

        $this->user = $user->load('grade.stage');
        view()->share('breadcrumbs', $this->breadcrumbs());
        $this->loadAnalytics();
    }

    public function breadcrumbs(): array
    {
        return [
            ['label' => __('lang.students'), 'url' => route('students')],
            ['label' => $this->user->name, 'icon' => 'o-user'],
        ];
    }

    private function loadAnalytics(): void
    {
        $allAttempts = ExamAttempt::where('user_id', $this->user->id)->orderBy('created_at')->get();
        
        $passedExams = $allAttempts->where('status', 'passed')->count();
        $failedExams = $allAttempts->where('status', 'failed')->count();
        $pendingExams = $allAttempts->where('status', null)->count();
        
        // Progress Chart Data (Line Chart)
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
            ],
            'options' => [
                'scales' => [
                    'x' => [
                        'title' => ['display' => true, 'text' => __('lang.date') ?? 'التاريخ'],
                        'ticks' => ['autoSkip' => false],
                    ],
                    'y' => [
                        'title' => ['display' => true, 'text' => __('lang.score') ?? 'الدرجة'],
                    ]
                ],
            ]
        ];

        // Status Chart Data (Doughnut Chart)
        $this->statusChart = [
            'type' => 'doughnut',
            'data' => [
                'labels' => [__('lang.passed') ?? 'ناجح', __('lang.failed') ?? 'راسب', __('lang.in_progress') ?? 'قيد الإجراء'],
                'datasets' => [
                    [
                        'data' => [$passedExams, $failedExams, $pendingExams],
                        'backgroundColor' => ['#10b981', '#ef4444', '#f59e0b'],
                    ]
                ]
            ]
        ];
    }

    public function render(): View
    {
        $allAttempts = ExamAttempt::where('user_id', $this->user->id)
            ->with('exam.week.semester')
            ->latest()
            ->get();
            
        $totalExamsTaken = $allAttempts->count();
        $averageScore = $totalExamsTaken > 0 ? $allAttempts->avg('total_score') : 0;
        
        return view('livewire.dashboard.student.student-profile', compact('allAttempts', 'totalExamsTaken', 'averageScore'));
    }
}
