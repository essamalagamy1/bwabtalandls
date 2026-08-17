<?php

namespace App\Livewire\Dashboard\Reports;

use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\StudentAnswer;
use App\Models\Stage;
use App\Models\Grade;
use App\Models\Semester;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('exam_reports')]
#[Lazy]
class ExamReports extends Component
{
    public array $averageScoreChart = [];

    public $stage_id;
    public $grade_id;
    public $semester_id;

    public function placeholder(): View
    {
        return view('livewire.placeholders.page-loading');
    }

    public function mount(): void
    {
        $this->authorize('show_exam_report');
        view()->share('breadcrumbs', $this->breadcrumbs());
        $this->loadCharts();
    }

    public function breadcrumbs(): array
    {
        return [
            ['label' => __('lang.exam_reports'), 'icon' => 'o-chart-bar'],
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

    private function getFilteredExamQuery()
    {
        $query = Exam::query();

        if ($this->stage_id) {
            $query->whereHas('week.semester.grade', function($q) {
                $q->where('stage_id', $this->stage_id);
            });
        }
        if ($this->grade_id) {
            $query->whereHas('week.semester', function($q) {
                $q->where('grade_id', $this->grade_id);
            });
        }
        if ($this->semester_id) {
            $query->whereHas('week', function($q) {
                $q->where('semester_id', $this->semester_id);
            });
        }

        return $query;
    }

    private function getFilteredAttemptQuery()
    {
        $query = ExamAttempt::query();

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

    private function getFilteredStudentAnswerQuery()
    {
        $query = StudentAnswer::query();

        if ($this->stage_id || $this->grade_id || $this->semester_id) {
            $query->whereHas('attempt', function($q) {
                if ($this->stage_id) {
                    $q->whereHas('exam.week.semester.grade', fn($sq) => $sq->where('stage_id', $this->stage_id));
                }
                if ($this->grade_id) {
                    $q->whereHas('exam.week.semester', fn($sq) => $sq->where('grade_id', $this->grade_id));
                }
                if ($this->semester_id) {
                    $q->whereHas('exam.week', fn($sq) => $sq->where('semester_id', $this->semester_id));
                }
            });
        }

        return $query;
    }

    private function loadCharts(): void
    {
        $exams = (clone $this->getFilteredExamQuery())->withAvg('attempts', 'total_score')
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();

        $labels = [];
        $data = [];
        foreach ($exams as $exam) {
            $labels[] = substr($exam->title, 0, 15) . "..."; 
            $data[] = round($exam->attempts_avg_total_score ?? 0, 2);
        }

        $this->averageScoreChart = [
            'type' => 'bar',
            'data' => [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => __('lang.average_score'),
                        'data' => $data,
                        'backgroundColor' => '#25376F',
                    ]
                ]
            ]
        ];
    }

    public function render(): View
    {
        $totalExams = (clone $this->getFilteredExamQuery())->count();
        $totalAttempts = (clone $this->getFilteredAttemptQuery())->count();
        
        $hardestQuestions = (clone $this->getFilteredStudentAnswerQuery())
            ->select('question_id', DB::raw('SUM(is_correct) as correct_count'), DB::raw('COUNT(*) as total_attempts'))
            ->with('question')
            ->groupBy('question_id')
            ->havingRaw('COUNT(*) > 0')
            ->orderByRaw('(SUM(is_correct) / COUNT(*)) ASC')
            ->take(5)
            ->get();

        $easiestQuestions = (clone $this->getFilteredStudentAnswerQuery())
            ->select('question_id', DB::raw('SUM(is_correct) as correct_count'), DB::raw('COUNT(*) as total_attempts'))
            ->with('question')
            ->groupBy('question_id')
            ->havingRaw('COUNT(*) > 0')
            ->orderByRaw('(SUM(is_correct) / COUNT(*)) DESC')
            ->take(5)
            ->get();

        $difficultExams = (clone $this->getFilteredExamQuery())
            ->withCount(['attempts as pass_count' => function ($query) {
                $query->where('status', 'passed');
            }])
            ->withCount('attempts')
            ->having('attempts_count', '>', 0)
            ->orderByRaw('pass_count / attempts_count ASC')
            ->take(5)
            ->get();

        $stages = Stage::all();
        $grades = $this->stage_id ? Grade::where('stage_id', $this->stage_id)->get() : collect();
        $semesters = $this->grade_id ? Semester::where('grade_id', $this->grade_id)->get() : collect();

        return view('livewire.dashboard.reports.exam-reports', compact('totalExams', 'totalAttempts', 'hardestQuestions', 'easiestQuestions', 'difficultExams', 'stages', 'grades', 'semesters'));
    }
}
