<?php

namespace App\Livewire\Dashboard\Reports;

use App\Models\ExamAttempt;
use App\Models\Grade;
use App\Models\Section;
use App\Models\Semester;
use App\Models\Stage;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
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

    public $section_id;

    public $semester_id;

    public $student_id;

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
        $this->section_id = null;
        $this->semester_id = null;
        $this->student_id = null;
        $this->loadCharts();
    }

    public function updatedGradeId()
    {
        $this->section_id = null;
        $this->semester_id = null;
        $this->student_id = null;
        $this->loadCharts();
    }

    public function updatedSectionId()
    {
        $this->student_id = null;
        $this->loadCharts();
    }

    public function updatedSemesterId()
    {
        $this->loadCharts();
    }

    public function updatedStudentId()
    {
        $this->loadCharts();
    }

    private function getFilteredAttemptQuery()
    {
        $query = ExamAttempt::query();

        if ($this->stage_id) {
            $query->whereHas('exam.week.semester.grade', function ($q) {
                $q->where('stage_id', $this->stage_id);
            });
        }
        if ($this->grade_id) {
            $query->whereHas('exam.week.semester', function ($q) {
                $q->where('grade_id', $this->grade_id);
            });
        }
        if ($this->section_id) {
            $query->whereHas('user', function ($q) {
                $q->where('section_id', $this->section_id);
            });
        }
        if ($this->semester_id) {
            $query->whereHas('exam.week', function ($q) {
                $q->where('semester_id', $this->semester_id);
            });
        }
        if ($this->student_id) {
            $query->where('user_id', $this->student_id);
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
                    ],
                ],
            ],
        ];

        $monthlyPerformance = (clone $this->getFilteredAttemptQuery())
            ->selectRaw('MONTH(created_at) as month, AVG(total_score) as avg_score')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $labels = [];
        $data = [];
        foreach ($monthlyPerformance as $record) {
            $labels[] = Carbon::create(null, (int) $record->month, 1)->translatedFormat('F');
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
                        'tension' => 0.4,
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
                        'title' => ['display' => true, 'text' => __('lang.average_score')],
                    ],
                ],
            ],
        ];
    }

    public function render(): View
    {
        $totalAttempts = (clone $this->getFilteredAttemptQuery())->count();
        $avgScore = (clone $this->getFilteredAttemptQuery())->avg('total_score') ?? 0;

        $topStudents = User::role('student');
        if ($this->stage_id) {
            $topStudents->whereHas('grade', fn ($q) => $q->where('stage_id', $this->stage_id));
        }
        if ($this->grade_id) {
            $topStudents->where('grade_id', $this->grade_id);
        }
        if ($this->section_id) {
            $topStudents->where('section_id', $this->section_id);
        }

        if ($this->student_id) {
            $topStudents->where('id', $this->student_id);
        }

        $weakStudents = clone $topStudents;

        $topStudents = $topStudents->withAvg(['examAttempts' => function ($q) {
            if ($this->semester_id) {
                $q->whereHas('exam.week', fn ($query) => $query->where('semester_id', $this->semester_id));
            }
        }], 'total_score')
            ->orderByDesc('exam_attempts_avg_total_score')
            ->take(5)
            ->get();

        $weakStudents = $weakStudents->withAvg(['examAttempts' => function ($q) {
            if ($this->semester_id) {
                $q->whereHas('exam.week', fn ($query) => $query->where('semester_id', $this->semester_id));
            }
        }], 'total_score')
            ->having('exam_attempts_avg_total_score', '<', 60)
            ->having('exam_attempts_avg_total_score', '>', 0)
            ->orderBy('exam_attempts_avg_total_score')
            ->take(5)
            ->get();

        $stages = Stage::all();
        $grades = $this->stage_id ? Grade::where('stage_id', $this->stage_id)->get() : collect();

        $sectionsQuery = Section::with('grade.stage');
        if ($this->grade_id) {
            $sectionsQuery->where('grade_id', $this->grade_id);
        } elseif ($this->stage_id) {
            $sectionsQuery->whereHas('grade', fn ($q) => $q->where('stage_id', $this->stage_id));
        }
        $sections = ($this->grade_id || $this->stage_id)
            ? $sectionsQuery->get()->map(function ($section) {
                $extra = array_filter([$section->grade?->name, $section->grade?->stage?->name]);

                return [
                    'id' => $section->id,
                    'name' => $section->name.($extra ? ' ('.implode(' - ', $extra).')' : ''),
                ];
            })
            : collect();

        $semestersQuery = Semester::with('grade.stage');
        if ($this->grade_id) {
            $semestersQuery->where('grade_id', $this->grade_id);
        } elseif ($this->stage_id) {
            $semestersQuery->whereHas('grade', fn ($q) => $q->where('stage_id', $this->stage_id));
        }
        $semesters = ($this->grade_id || $this->stage_id)
            ? $semestersQuery->get()->map(function ($semester) {
                $extra = array_filter([$semester->grade?->name, $semester->grade?->stage?->name]);

                return [
                    'id' => $semester->id,
                    'name' => $semester->name_with_academic_year.($extra ? ' ('.implode(' - ', $extra).')' : ''),
                ];
            })
            : collect();

        $studentsQuery = User::role('student')->with('grade.stage', 'section');
        if ($this->section_id) {
            $studentsQuery->where('section_id', $this->section_id);
        } elseif ($this->grade_id) {
            $studentsQuery->where('grade_id', $this->grade_id);
        } elseif ($this->stage_id) {
            $studentsQuery->whereHas('grade', fn ($q) => $q->where('stage_id', $this->stage_id));
        }
        $students = ($this->stage_id || $this->grade_id || $this->section_id)
            ? $studentsQuery->get()->map(function ($student) {
                $extra = array_filter([$student->section?->name, $student->grade?->name, $student->grade?->stage?->name]);

                return [
                    'id' => $student->id,
                    'name' => $student->name.($extra ? ' ('.implode(' - ', $extra).')' : ''),
                ];
            })
            : User::role('student')->with('grade.stage')->get()->map(function ($student) {
                $extra = array_filter([$student->grade?->name, $student->grade?->stage?->name]);

                return [
                    'id' => $student->id,
                    'name' => $student->name.($extra ? ' ('.implode(' - ', $extra).')' : ''),
                ];
            });

        $selectedStage = $this->stage_id ? (Stage::find($this->stage_id)?->name ?? null) : null;
        $selectedGrade = $this->grade_id ? (Grade::find($this->grade_id)?->name ?? null) : null;
        $selectedSection = $this->section_id ? (Section::find($this->section_id)?->name ?? null) : null;
        $selectedSemester = $this->semester_id ? (Semester::find($this->semester_id)?->name_with_academic_year ?? null) : null;
        $selectedStudent = $this->student_id ? (User::find($this->student_id)?->name ?? null) : null;

        return view('livewire.dashboard.reports.student-reports', compact(
            'totalAttempts', 'avgScore', 'topStudents', 'weakStudents',
            'stages', 'grades', 'sections', 'semesters', 'students',
            'selectedStage', 'selectedGrade', 'selectedSection', 'selectedSemester', 'selectedStudent',
        ));
    }

    public function printReport()
    {
        $url = route('reports.students.print', array_filter([
            'stage_id' => $this->stage_id,
            'grade_id' => $this->grade_id,
            'section_id' => $this->section_id,
            'semester_id' => $this->semester_id,
            'student_id' => $this->student_id,
        ]));

        $this->js("window.open('{$url}', '_blank')");
    }
}
