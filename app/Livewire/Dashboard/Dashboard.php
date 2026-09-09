<?php

namespace App\Livewire\Dashboard;

use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\Grade;
use App\Models\Question;
use App\Models\Section;
use App\Models\Semester;
use App\Models\Stage;
use App\Models\Training;
use App\Models\User;
use App\Models\Week;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
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

    public $section_id;

    public $semester_id;

    // Filter options
    public array $stages = [];

    public array $grades = [];

    public array $sections = [];

    public array $semesters = [];

    // Charts
    public array $studentsPerGradeChart = [];

    public array $studentStatusChart = [];

    public array $examScoresChart = [];

    public array $newStudentsMonthlyChart = [];
    public array $ticketStats = [];

    public function mount(): void
    {
        // Redirect parent accounts to their own dashboard
        if (auth()->user()->isParent()) {
            $this->redirectRoute('parent.dashboard', navigate: true);

            return;
        }

        view()->share('breadcrumbs', $this->breadcrumbs());
        $this->stages = Stage::get(['id', 'name'])->toArray();
        $this->loadCharts();
        $this->loadTicketStats();
    }

    private function loadTicketStats(): void
    {
        $query = \App\Models\Ticket::query();

        // If user is a student (not admin), only show their own stats
        if (auth()->user()->hasRole('student') && !auth()->user()->hasRole('admin')) {
            $query->where('user_id', auth()->id());
        } elseif (!auth()->user()->hasPermissionTo('show_ticket')) {
            // If they are admin but don't have permission to see all tickets, show none
            $this->ticketStats = ['open' => 0, 'in_progress' => 0, 'closed' => 0];
            return;
        }

        $this->ticketStats = [
            'total' => (clone $query)->count(),
            'open' => (clone $query)->where('status', 'open')->count(),
            'in_progress' => (clone $query)->where('status', 'in_progress')->count(),
            'closed' => (clone $query)->where('status', 'closed')->count(),
        ];
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
        $this->section_id = null;
        $this->semester_id = null;
        if ($this->stage_id) {
            $this->grades = Grade::where('stage_id', $this->stage_id)->get(['id', 'name'])->toArray();
            $this->sections = Section::with('grade.stage')
                ->whereHas('grade', fn ($q) => $q->where('stage_id', $this->stage_id))
                ->get()
                ->map(fn ($section) => [
                    'id' => $section->id,
                    'name' => $section->name.($section->grade ? ' ('.$section->grade->name.($section->grade->stage ? ' - '.$section->grade->stage->name : '').')' : ''),
                ])->toArray();
            $this->semesters = Semester::with('grade.stage')
                ->whereHas('grade', fn ($q) => $q->where('stage_id', $this->stage_id))
                ->get()
                ->map(fn ($semester) => [
                    'id' => $semester->id,
                    'name' => $semester->name_with_academic_year.($semester->grade ? ' ('.$semester->grade->name.($semester->grade->stage ? ' - '.$semester->grade->stage->name : '').')' : ''),
                ])->toArray();
        } else {
            $this->grades = [];
            $this->sections = [];
            $this->semesters = [];
        }
        $this->loadCharts();
    }

    public function updatedGradeId(): void
    {
        $this->section_id = null;
        $this->semester_id = null;
        if ($this->grade_id) {
            $this->sections = Section::with('grade.stage')
                ->where('grade_id', $this->grade_id)
                ->get()
                ->map(fn ($section) => [
                    'id' => $section->id,
                    'name' => $section->name.($section->grade ? ' ('.$section->grade->name.($section->grade->stage ? ' - '.$section->grade->stage->name : '').')' : ''),
                ])->toArray();
            $this->semesters = Semester::with('grade.stage')
                ->where('grade_id', $this->grade_id)
                ->get()
                ->map(fn ($semester) => [
                    'id' => $semester->id,
                    'name' => $semester->name_with_academic_year.($semester->grade ? ' ('.$semester->grade->name.($semester->grade->stage ? ' - '.$semester->grade->stage->name : '').')' : ''),
                ])->toArray();
        } elseif ($this->stage_id) {
            $this->sections = Section::with('grade.stage')
                ->whereHas('grade', fn ($q) => $q->where('stage_id', $this->stage_id))
                ->get()
                ->map(fn ($section) => [
                    'id' => $section->id,
                    'name' => $section->name.($section->grade ? ' ('.$section->grade->name.($section->grade->stage ? ' - '.$section->grade->stage->name : '').')' : ''),
                ])->toArray();
            $this->semesters = Semester::with('grade.stage')
                ->whereHas('grade', fn ($q) => $q->where('stage_id', $this->stage_id))
                ->get()
                ->map(fn ($semester) => [
                    'id' => $semester->id,
                    'name' => $semester->name_with_academic_year.($semester->grade ? ' ('.$semester->grade->name.($semester->grade->stage ? ' - '.$semester->grade->stage->name : '').')' : ''),
                ])->toArray();
        } else {
            $this->sections = [];
            $this->semesters = [];
        }
        $this->loadCharts();
    }

    public function updatedSectionId(): void
    {
        $this->loadCharts();
    }

    public function updatedSemesterId(): void
    {
        $this->loadCharts();
    }

    // ─── Filtered Query Builders ───────────────────────────────────────

    private function baseFilteredStudentQuery(): Builder
    {
        return User::role('student')
            ->when($this->stage_id, fn (Builder $q) => $q->whereHas('grade', fn ($gq) => $gq->where('stage_id', $this->stage_id)))
            ->when($this->grade_id, fn (Builder $q) => $q->where('grade_id', $this->grade_id))
            ->when($this->section_id, fn (Builder $q) => $q->where('section_id', $this->section_id));
    }

    private function filteredStudentQuery(): Builder
    {
        return $this->baseFilteredStudentQuery();
    }

    private function filteredGradeQuery(): Builder
    {
        return Grade::query()
            ->when($this->stage_id, fn (Builder $q) => $q->where('stage_id', $this->stage_id))
            ->when($this->grade_id, fn (Builder $q) => $q->where('id', $this->grade_id));
    }

    private function filteredSemesterQuery(): Builder
    {
        return Semester::query()
            ->when($this->stage_id, fn (Builder $q) => $q->whereHas('grade', fn ($gq) => $gq->where('stage_id', $this->stage_id)))
            ->when($this->grade_id, fn (Builder $q) => $q->where('grade_id', $this->grade_id))
            ->when($this->semester_id, fn (Builder $q) => $q->where('id', $this->semester_id));
    }

    private function filteredWeekQuery(): Builder
    {
        return Week::query()
            ->when($this->stage_id, fn (Builder $q) => $q->whereHas('semester.grade', fn ($gq) => $gq->where('stage_id', $this->stage_id)))
            ->when($this->grade_id, fn (Builder $q) => $q->whereHas('semester', fn ($sq) => $sq->where('grade_id', $this->grade_id)))
            ->when($this->semester_id, fn (Builder $q) => $q->where('semester_id', $this->semester_id));
    }

    private function filteredExamQuery(): Builder
    {
        return Exam::query()
            ->when($this->stage_id, fn (Builder $q) => $q->whereHas('week.semester.grade', fn ($gq) => $gq->where('stage_id', $this->stage_id)))
            ->when($this->grade_id, fn (Builder $q) => $q->whereHas('week.semester', fn ($sq) => $sq->where('grade_id', $this->grade_id)))
            ->when($this->semester_id, fn (Builder $q) => $q->whereHas('week', fn ($wq) => $wq->where('semester_id', $this->semester_id)));
    }

    private function filteredTrainingQuery(): Builder
    {
        return Training::query()
            ->when($this->stage_id, fn (Builder $q) => $q->whereHas('week.semester.grade', fn ($gq) => $gq->where('stage_id', $this->stage_id)))
            ->when($this->grade_id, fn (Builder $q) => $q->whereHas('week.semester', fn ($sq) => $sq->where('grade_id', $this->grade_id)))
            ->when($this->semester_id, fn (Builder $q) => $q->whereHas('week', fn ($wq) => $wq->where('semester_id', $this->semester_id)));
    }

    private function filteredQuestionQuery(): Builder
    {
        return Question::query()
            ->when($this->stage_id, fn (Builder $q) => $q->whereHas('exam.week.semester.grade', fn ($gq) => $gq->where('stage_id', $this->stage_id)))
            ->when($this->grade_id, fn (Builder $q) => $q->whereHas('exam.week.semester', fn ($sq) => $sq->where('grade_id', $this->grade_id)))
            ->when($this->semester_id, fn (Builder $q) => $q->whereHas('exam.week', fn ($wq) => $wq->where('semester_id', $this->semester_id)));
    }

    private function filteredAttemptQuery(): Builder
    {
        return ExamAttempt::query()
            ->when($this->stage_id, fn (Builder $q) => $q->whereHas('exam.week.semester.grade', fn ($gq) => $gq->where('stage_id', $this->stage_id)))
            ->when($this->grade_id, fn (Builder $q) => $q->whereHas('exam.week.semester', fn ($sq) => $sq->where('grade_id', $this->grade_id)))
            ->when($this->semester_id, fn (Builder $q) => $q->whereHas('exam.week', fn ($wq) => $wq->where('semester_id', $this->semester_id)))
            ->when($this->section_id, fn (Builder $q) => $q->whereHas('user', fn ($uq) => $uq->where('section_id', $this->section_id)));
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
            ->when($this->stage_id, fn (Builder $q) => $q->where('stage_id', $this->stage_id))
            ->when($this->grade_id, fn (Builder $q) => $q->where('id', $this->grade_id))
            ->withCount(['users' => fn ($q) => $q->role('student')])
            ->get();

        $this->studentsPerGradeChart = [
            'type' => 'bar',
            'data' => [
                'labels' => $grades->map(fn ($g) => explode("\n", wordwrap($g->name, 15, "\n")))->toArray(),
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
                'scales' => [
                    'x' => [
                        'title' => ['display' => true, 'text' => __('lang.grade')],
                        'ticks' => ['autoSkip' => false],
                    ],
                    'y' => [
                        'title' => ['display' => true, 'text' => __('lang.students')],
                    ],
                ],
            ],
        ];
    }

    private function loadStudentStatusChart(): void
    {
        $active = (clone $this->baseFilteredStudentQuery())->where('status', 'active')->count();
        $inactive = (clone $this->baseFilteredStudentQuery())->where('status', 'inactive')->count();
        $pending = (clone $this->baseFilteredStudentQuery())->where('status', 'pending')->count();

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
                'labels' => $exams->map(fn ($e) => explode("\n", wordwrap($e->title, 15, "\n")))->toArray(),
                'datasets' => [
                    [
                        'label' => __('lang.average_score'),
                        'data' => $exams->map(fn ($e) => round($e->attempts_avg_total_score ?? 0, 1))->toArray(),
                        'backgroundColor' => '#25376F',
                        'borderRadius' => 8,
                    ],
                ],
            ],
            'options' => [
                'plugins' => [
                    'legend' => ['display' => false],
                ],
                'scales' => [
                    'x' => [
                        'title' => ['display' => true, 'text' => __('lang.exam')],
                        'ticks' => ['autoSkip' => false],
                    ],
                    'y' => [
                        'title' => ['display' => true, 'text' => __('lang.average_score')],
                    ],
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
            'options' => [
                'scales' => [
                    'x' => [
                        'title' => ['display' => true, 'text' => __('lang.month') ?? 'الشهر'],
                        'ticks' => ['autoSkip' => false],
                    ],
                    'y' => [
                        'title' => ['display' => true, 'text' => __('lang.students') ?? 'الطلاب'],
                    ],
                ],
            ],
        ];
    }

    public function printReport()
    {
        $url = route('dashboard.print', array_filter([
            'stage_id' => $this->stage_id,
            'grade_id' => $this->grade_id,
            'section_id' => $this->section_id,
            'semester_id' => $this->semester_id,
        ]));

        $this->js("window.open('{$url}', '_blank')");
    }

    // ─── Render ─────────────────────────────────────────────────────────

    public function render(): View
    {
        // Stats row 1
        $totalStudents = (clone $this->baseFilteredStudentQuery())->count();
        $activeStudents = (clone $this->baseFilteredStudentQuery())->where('status', 'active')->count();
        $inactiveStudents = (clone $this->baseFilteredStudentQuery())->whereIn('status', ['inactive', 'pending'])->count();
        $totalStages = Stage::when($this->stage_id, fn ($q) => $q->where('id', $this->stage_id))->count();
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
        $totalInstructors = User::whereHas('roles', fn ($q) => $q->where('name', 'instructor'))->count();

        // Latest students
        $latestStudents = (clone $this->filteredStudentQuery())
            ->with('grade.stage')
            ->latest()
            ->take(5)
            ->get();

        // Selected filter names for printing
        $selectedStage = $this->stage_id ? (Stage::find($this->stage_id)?->name ?? null) : null;
        $selectedGrade = $this->grade_id ? (Grade::find($this->grade_id)?->name ?? null) : null;
        $selectedSection = $this->section_id ? (Section::find($this->section_id)?->name ?? null) : null;
        $selectedSemester = $this->semester_id ? (Semester::find($this->semester_id)?->name_with_academic_year ?? null) : null;

        return view('livewire.dashboard.dashboard', compact(
            'totalStudents', 'activeStudents', 'inactiveStudents', 'totalStages', 'totalGrades',
            'totalSemesters', 'totalWeeks', 'totalTrainings', 'totalExams',
            'totalQuestions', 'totalAttempts', 'avgScore', 'passRate', 'totalInstructors',
            'latestStudents',
            'selectedStage', 'selectedGrade', 'selectedSection', 'selectedSemester',
        ));
    }
}
