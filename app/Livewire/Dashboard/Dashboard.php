<?php

namespace App\Livewire\Dashboard;

use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\Grade;
use App\Models\Question;
use App\Models\Semester;
use App\Models\Stage;
use App\Models\Training;
use App\Models\User;
use App\Models\Week;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('home')]
class Dashboard extends Component
{
    use WithPagination;

    // Filters
    public $stage_id;
    public $grade_id;
    public $semester_id;

    // Filter options
    public array $stages = [];
    public array $grades = [];
    public array $semesters = [];

    // Charts
    public array $studentsPerGradeChart = [];
    public array $studentStatusChart = [];
    public array $examScoresChart = [];
    public array $newStudentsMonthlyChart = [];

    public function mount(): void
    {
        view()->share('breadcrumbs', $this->breadcrumbs());
        $this->stages = Stage::where('is_active', true)->get(['id', 'name'])->toArray();
        $this->loadCharts();
    }

    public function breadcrumbs(): array
    {
        return [
            [
                'label' => __('lang.home'),
                'icon' => 'o-home',
            ],
        ];
    }

    public function updatedStageId(): void
    {
        $this->grade_id = null;
        $this->semester_id = null;
        $this->grades = $this->stage_id
            ? Grade::where('stage_id', $this->stage_id)->where('is_active', true)->get(['id', 'name'])->toArray()
            : [];
        $this->semesters = [];
        $this->loadCharts();
    }

    public function updatedGradeId(): void
    {
        $this->semester_id = null;
        $this->semesters = $this->grade_id
            ? Semester::where('grade_id', $this->grade_id)->where('is_active', true)->get(['id', 'name'])->toArray()
            : [];
        $this->loadCharts();
    }

    public function updatedSemesterId(): void
    {
        $this->loadCharts();
    }

    // ─── Filtered Query Builders ───────────────────────────────────────

    private function filteredStudentQuery(): Builder
    {
        return User::role('student')
            ->when($this->stage_id, fn(Builder $q) => $q->whereHas('grade', fn($gq) => $gq->where('stage_id', $this->stage_id)))
            ->when($this->grade_id, fn(Builder $q) => $q->where('grade_id', $this->grade_id));
    }

    private function filteredGradeQuery(): Builder
    {
        return Grade::query()
            ->when($this->stage_id, fn(Builder $q) => $q->where('stage_id', $this->stage_id))
            ->when($this->grade_id, fn(Builder $q) => $q->where('id', $this->grade_id));
    }

    private function filteredSemesterQuery(): Builder
    {
        return Semester::query()
            ->when($this->stage_id, fn(Builder $q) => $q->whereHas('grade', fn($gq) => $gq->where('stage_id', $this->stage_id)))
            ->when($this->grade_id, fn(Builder $q) => $q->where('grade_id', $this->grade_id))
            ->when($this->semester_id, fn(Builder $q) => $q->where('id', $this->semester_id));
    }

    private function filteredWeekQuery(): Builder
    {
        return Week::query()
            ->when($this->stage_id, fn(Builder $q) => $q->whereHas('semester.grade', fn($gq) => $gq->where('stage_id', $this->stage_id)))
            ->when($this->grade_id, fn(Builder $q) => $q->whereHas('semester', fn($sq) => $sq->where('grade_id', $this->grade_id)))
            ->when($this->semester_id, fn(Builder $q) => $q->where('semester_id', $this->semester_id));
    }

    private function filteredExamQuery(): Builder
    {
        return Exam::query()
            ->when($this->stage_id, fn(Builder $q) => $q->whereHas('week.semester.grade', fn($gq) => $gq->where('stage_id', $this->stage_id)))
            ->when($this->grade_id, fn(Builder $q) => $q->whereHas('week.semester', fn($sq) => $sq->where('grade_id', $this->grade_id)))
            ->when($this->semester_id, fn(Builder $q) => $q->whereHas('week', fn($wq) => $wq->where('semester_id', $this->semester_id)));
    }

    private function filteredTrainingQuery(): Builder
    {
        return Training::query()
            ->when($this->stage_id, fn(Builder $q) => $q->whereHas('week.semester.grade', fn($gq) => $gq->where('stage_id', $this->stage_id)))
            ->when($this->grade_id, fn(Builder $q) => $q->whereHas('week.semester', fn($sq) => $sq->where('grade_id', $this->grade_id)))
            ->when($this->semester_id, fn(Builder $q) => $q->whereHas('week', fn($wq) => $wq->where('semester_id', $this->semester_id)));
    }

    private function filteredQuestionQuery(): Builder
    {
        return Question::query()
            ->when($this->stage_id, fn(Builder $q) => $q->whereHas('exam.week.semester.grade', fn($gq) => $gq->where('stage_id', $this->stage_id)))
            ->when($this->grade_id, fn(Builder $q) => $q->whereHas('exam.week.semester', fn($sq) => $sq->where('grade_id', $this->grade_id)))
            ->when($this->semester_id, fn(Builder $q) => $q->whereHas('exam.week', fn($wq) => $wq->where('semester_id', $this->semester_id)));
    }

    private function filteredAttemptQuery(): Builder
    {
        return ExamAttempt::query()
            ->when($this->stage_id, fn(Builder $q) => $q->whereHas('exam.week.semester.grade', fn($gq) => $gq->where('stage_id', $this->stage_id)))
            ->when($this->grade_id, fn(Builder $q) => $q->whereHas('exam.week.semester', fn($sq) => $sq->where('grade_id', $this->grade_id)))
            ->when($this->semester_id, fn(Builder $q) => $q->whereHas('exam.week', fn($wq) => $wq->where('semester_id', $this->semester_id)));
    }

    // ─── Charts ────────────────────────────────────────────────────────

    private function loadCharts(): void
    {
        $this->loadStudentsPerGradeChart();
        $this->loadStudentStatusChart();
        $this->loadExamScoresChart();
        $this->loadNewStudentsMonthlyChart();
    }

    private function loadStudentsPerGradeChart(): void
    {
        $grades = Grade::query()
            ->when($this->stage_id, fn(Builder $q) => $q->where('stage_id', $this->stage_id))
            ->when($this->grade_id, fn(Builder $q) => $q->where('id', $this->grade_id))
            ->withCount(['users' => fn($q) => $q->role('student')])
            ->get();

        $this->studentsPerGradeChart = [
            'type' => 'bar',
            'data' => [
                'labels' => $grades->pluck('name')->toArray(),
                'datasets' => [
                    [
                        'label' => __('lang.students'),
                        'data' => $grades->pluck('users_count')->toArray(),
                        'backgroundColor' => [
                            '#6366f1', '#8b5cf6', '#a78bfa', '#c4b5fd',
                            '#818cf8', '#7c3aed', '#5b21b6', '#4f46e5',
                            '#4338ca', '#3730a3',
                        ],
                        'borderRadius' => 8,
                    ],
                ],
            ],
            'options' => [
                'plugins' => [
                    'legend' => ['display' => false],
                ],
            ],
        ];
    }

    private function loadStudentStatusChart(): void
    {
        $active = (clone $this->filteredStudentQuery())->where('status', 'active')->count();
        $inactive = (clone $this->filteredStudentQuery())->where('status', 'inactive')->count();
        $pending = (clone $this->filteredStudentQuery())->where('status', 'pending')->count();

        $this->studentStatusChart = [
            'type' => 'doughnut',
            'data' => [
                'labels' => [__('lang.active'), __('lang.inactive'), __('lang.pending')],
                'datasets' => [
                    [
                        'data' => [$active, $inactive, $pending],
                        'backgroundColor' => ['#22c55e', '#ef4444', '#f59e0b'],
                        'borderWidth' => 0,
                    ],
                ],
            ],
        ];
    }

    private function loadExamScoresChart(): void
    {
        $exams = (clone $this->filteredExamQuery())
            ->withAvg('attempts', 'total_score')
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();

        $this->examScoresChart = [
            'type' => 'bar',
            'data' => [
                'labels' => $exams->map(fn($e) => mb_substr($e->title, 0, 15))->toArray(),
                'datasets' => [
                    [
                        'label' => __('lang.average_score'),
                        'data' => $exams->map(fn($e) => round($e->attempts_avg_total_score ?? 0, 1))->toArray(),
                        'backgroundColor' => '#25376F',
                        'borderRadius' => 8,
                    ],
                ],
            ],
            'options' => [
                'plugins' => [
                    'legend' => ['display' => false],
                ],
            ],
        ];
    }

    private function loadNewStudentsMonthlyChart(): void
    {
        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = (clone $this->filteredStudentQuery())
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $months->push([
                'label' => $date->translatedFormat('M Y'),
                'count' => $count,
            ]);
        }

        $this->newStudentsMonthlyChart = [
            'type' => 'line',
            'data' => [
                'labels' => $months->pluck('label')->toArray(),
                'datasets' => [
                    [
                        'label' => __('lang.students'),
                        'data' => $months->pluck('count')->toArray(),
                        'borderColor' => '#6366f1',
                        'backgroundColor' => 'rgba(99, 102, 241, 0.1)',
                        'fill' => true,
                        'tension' => 0.4,
                        'pointBackgroundColor' => '#6366f1',
                        'pointRadius' => 5,
                    ],
                ],
            ],
        ];
    }

    // ─── Render ─────────────────────────────────────────────────────────

    public function render(): View
    {
        // Stats row 1
        $totalStudents = (clone $this->filteredStudentQuery())->count();
        $activeStudents = (clone $this->filteredStudentQuery())->where('status', 'active')->count();
        $totalStages = Stage::when($this->stage_id, fn($q) => $q->where('id', $this->stage_id))->count();
        $totalGrades = (clone $this->filteredGradeQuery())->count();

        // Stats row 2
        $totalSemesters = (clone $this->filteredSemesterQuery())->count();
        $totalWeeks = (clone $this->filteredWeekQuery())->count();
        $totalTrainings = (clone $this->filteredTrainingQuery())->count();
        $totalExams = (clone $this->filteredExamQuery())->count();

        // Stats row 3
        $totalQuestions = (clone $this->filteredQuestionQuery())->count();
        $totalAttempts = (clone $this->filteredAttemptQuery())->count();
        $avgScore = round((clone $this->filteredAttemptQuery())->avg('total_score') ?? 0, 1);
        $passRate = $totalAttempts > 0
            ? round(((clone $this->filteredAttemptQuery())->where('status', 'passed')->count() / $totalAttempts) * 100, 1)
            : 0;
        $totalInstructors = User::whereHas('roles', fn($q) => $q->where('name', 'instructor'))->count();

        // Latest students
        $latestStudents = (clone $this->filteredStudentQuery())
            ->with('grade.stage')
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.dashboard.dashboard', compact(
            'totalStudents', 'activeStudents', 'totalStages', 'totalGrades',
            'totalSemesters', 'totalWeeks', 'totalTrainings', 'totalExams',
            'totalQuestions', 'totalAttempts', 'avgScore', 'passRate', 'totalInstructors',
            'latestStudents',
        ));
    }
}
