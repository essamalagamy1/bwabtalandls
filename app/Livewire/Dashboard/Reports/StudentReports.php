<?php

namespace App\Livewire\Dashboard\Reports;

use App\Models\ExamAttempt;
use App\Models\User;
use App\Models\Stage;
use App\Models\Grade;
use App\Models\Semester;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('student_reports')]
#[Lazy]
class StudentReports extends Component
{
    public array $passFailChart = [];
    public array $performanceChart = [];

    public $stage_id;
    public $grade_id;
    public $semester_id;

    public function placeholder(): View
    {
        return view('livewire.placeholders.page-loading');
    }

    public function mount(): void
    {
        $this->authorize('show_student_report');
        view()->share('breadcrumbs', $this->breadcrumbs());
        $this->loadCharts();
    }

    public function breadcrumbs(): array
    {
        return [
            ['label' => __('lang.student_reports'), 'icon' => 'o-chart-pie'],
        ];
    }

    public function updatedStageId()
    {
        $this->grade_id = null;
        $this->semester_id = null;
        $this->loadCharts();
    }

    public function updatedGradeId()
    {
        $this->semester_id = null;
        $this->loadCharts();
    }

    public function updatedSemesterId()
    {
        $this->loadCharts();
    }

    private function getFilteredAttemptQuery()
    {
        $query = ExamAttempt::query()
            ->whereHas('user', fn($q) => $q->where('status', 'active'))
            ->whereHas('exam', fn($q) => $q->where('is_active', true)->whereHas('week', fn($wq) => $wq->where('is_active', true)));

        if ($this->stage_id) {
            $query->whereHas('exam.week.semester.grade', function($q) {
                $q->where('stage_id', $this->stage_id);
            });
        }
        if ($this->grade_id) {
            $query->whereHas('exam.week.semester', function($q) {
                $q->where('grade_id', $this->grade_id);
            });
        }
        if ($this->semester_id) {
            $query->whereHas('exam.week', function($q) {
                $q->where('semester_id', $this->semester_id);
            });
        }

        return $query;
    }

    private function loadCharts(): void
    {
        $passed = (clone $this->getFilteredAttemptQuery())->where('status', 'passed')->count();
        $failed = (clone $this->getFilteredAttemptQuery())->where('status', 'failed')->count();

        $this->passFailChart = [
            'type' => 'pie',
            'data' => [
                'labels' => [__('lang.passed'), __('lang.failed')],
                'datasets' => [
                    [
                        'label' => __('lang.students'),
                        'data' => [$passed, $failed],
                        'backgroundColor' => ['#4ade80', '#f87171'],
                    ]
                ]
            ]
        ];

        $monthlyPerformance = (clone $this->getFilteredAttemptQuery())
            ->selectRaw('MONTH(created_at) as month, AVG(total_score) as avg_score')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $labels = [];
        $data = [];
        foreach ($monthlyPerformance as $record) {
            $labels[] = date("F", mktime(0, 0, 0, $record->month, 10));
            $data[] = round($record->avg_score, 2);
        }

        $this->performanceChart = [
            'type' => 'line',
            'data' => [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => __('lang.average_score'),
                        'data' => $data,
                        'borderColor' => '#3b82f6',
                        'tension' => 0.4
                    ]
                ]
            ]
        ];
    }

    public function render(): View
    {
        $totalAttempts = (clone $this->getFilteredAttemptQuery())->count();
        $avgScore = (clone $this->getFilteredAttemptQuery())->avg('total_score') ?? 0;
        
        $topStudents = User::role('student')->where('status', 'active');
        if ($this->stage_id) {
            $topStudents->whereHas('grade', fn($q) => $q->where('stage_id', $this->stage_id));
        }
        if ($this->grade_id) {
            $topStudents->where('grade_id', $this->grade_id);
        }

        $weakStudents = clone $topStudents;

        $topStudents = $topStudents->withAvg(['examAttempts' => function($q) {
                if ($this->semester_id) {
                    $q->whereHas('exam.week', fn($query) => $query->where('semester_id', $this->semester_id));
                }
            }], 'total_score')
            ->orderByDesc('exam_attempts_avg_total_score')
            ->take(5)
            ->get();

        $weakStudents = $weakStudents->withAvg(['examAttempts' => function($q) {
                if ($this->semester_id) {
                    $q->whereHas('exam.week', fn($query) => $query->where('semester_id', $this->semester_id));
                }
            }], 'total_score')
            ->having('exam_attempts_avg_total_score', '<', 60)
            ->having('exam_attempts_avg_total_score', '>', 0)
            ->orderBy('exam_attempts_avg_total_score')
            ->take(5)
            ->get();

        $stages = Stage::where('is_active', true)->get();
        $grades = $this->stage_id ? Grade::where('stage_id', $this->stage_id)->where('is_active', true)->get() : collect();
        $semesters = $this->grade_id ? Semester::where('grade_id', $this->grade_id)->where('is_active', true)->get() : collect();

        return view('livewire.dashboard.reports.student-reports', compact('totalAttempts', 'avgScore', 'topStudents', 'weakStudents', 'stages', 'grades', 'semesters'));
    }
}
