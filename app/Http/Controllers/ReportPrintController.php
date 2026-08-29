<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\Grade;
use App\Models\Question;
use App\Models\Section;
use App\Models\Semester;
use App\Models\Stage;
use App\Models\StudentAnswer;
use App\Models\Training;
use App\Models\User;
use App\Models\Week;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportPrintController extends Controller
{
    public function dashboard(Request $request)
    {
        $stage_id = $request->get('stage_id');
        $grade_id = $request->get('grade_id');
        $section_id = $request->get('section_id');
        $semester_id = $request->get('semester_id');

        $selectedStage = $stage_id ? (Stage::find($stage_id)?->name ?? null) : null;
        $selectedGrade = $grade_id ? (Grade::find($grade_id)?->name ?? null) : null;
        $selectedSection = $section_id ? (Section::find($section_id)?->name ?? null) : null;
        $selectedSemester = $semester_id ? (Semester::find($semester_id)?->name_with_academic_year ?? null) : null;

        // Base Student Query
        $baseStudentQuery = User::role('student')
            ->when($stage_id, fn ($q) => $q->whereHas('grade', fn ($gq) => $gq->where('stage_id', $stage_id)))
            ->when($grade_id, fn ($q) => $q->where('grade_id', $grade_id))
            ->when($section_id, fn ($q) => $q->where('section_id', $section_id));

        // Stats Row 1: Students
        $totalStudents = (clone $baseStudentQuery)->count();
        $activeStudents = (clone $baseStudentQuery)->where('status', 'active')->count();
        $inactiveStudents = (clone $baseStudentQuery)->whereIn('status', ['inactive', 'pending'])->count();

        // Stats Row 2: Structure
        $totalStages = Stage::when($stage_id, fn ($q) => $q->where('id', $stage_id))->count();
        $totalGrades = Grade::when($stage_id, fn ($q) => $q->where('stage_id', $stage_id))
            ->when($grade_id, fn ($q) => $q->where('id', $grade_id))->count();
        $totalSemesters = Semester::when($stage_id, fn ($q) => $q->whereHas('grade', fn ($gq) => $gq->where('stage_id', $stage_id)))
            ->when($grade_id, fn ($q) => $q->where('grade_id', $grade_id))
            ->when($semester_id, fn ($q) => $q->where('id', $semester_id))->count();
        $totalWeeks = Week::when($stage_id, fn ($q) => $q->whereHas('semester.grade', fn ($gq) => $gq->where('stage_id', $stage_id)))
            ->when($grade_id, fn ($q) => $q->whereHas('semester', fn ($sq) => $sq->where('grade_id', $grade_id)))
            ->when($semester_id, fn ($q) => $q->where('semester_id', $semester_id))->count();

        // Stats Row 3: Exams & Attempts
        $examQuery = Exam::when($stage_id, fn ($q) => $q->whereHas('week.semester.grade', fn ($gq) => $gq->where('stage_id', $stage_id)))
            ->when($grade_id, fn ($q) => $q->whereHas('week.semester', fn ($sq) => $sq->where('grade_id', $grade_id)))
            ->when($semester_id, fn ($q) => $q->whereHas('week', fn ($wq) => $wq->where('semester_id', $semester_id)));

        $totalTrainings = Training::when($stage_id, fn ($q) => $q->whereHas('week.semester.grade', fn ($gq) => $gq->where('stage_id', $stage_id)))
            ->when($grade_id, fn ($q) => $q->whereHas('week.semester', fn ($sq) => $sq->where('grade_id', $grade_id)))
            ->when($semester_id, fn ($q) => $q->whereHas('week', fn ($wq) => $wq->where('semester_id', $semester_id)))->count();

        $totalExams = (clone $examQuery)->count();

        $totalQuestions = Question::when($stage_id, fn ($q) => $q->whereHas('exam.week.semester.grade', fn ($gq) => $gq->where('stage_id', $stage_id)))
            ->when($grade_id, fn ($q) => $q->whereHas('exam.week.semester', fn ($sq) => $sq->where('grade_id', $grade_id)))
            ->when($semester_id, fn ($q) => $q->whereHas('exam.week', fn ($wq) => $wq->where('semester_id', $semester_id)))->count();

        $attemptQuery = ExamAttempt::when($stage_id, fn ($q) => $q->whereHas('exam.week.semester.grade', fn ($gq) => $gq->where('stage_id', $stage_id)))
            ->when($grade_id, fn ($q) => $q->whereHas('exam.week.semester', fn ($sq) => $sq->where('grade_id', $grade_id)))
            ->when($semester_id, fn ($q) => $q->whereHas('exam.week', fn ($wq) => $wq->where('semester_id', $semester_id)))
            ->when($section_id, fn ($q) => $q->whereHas('user', fn ($uq) => $uq->where('section_id', $section_id)));

        $totalAttempts = (clone $attemptQuery)->count();

        // Stats Row 4: Performance
        $avgScore = round((clone $attemptQuery)->avg('total_score') ?? 0, 1);
        $passRate = $totalAttempts > 0 ? round(((clone $attemptQuery)->where('status', 'passed')->count() / $totalAttempts) * 100, 1) : 0;

        // Chart 1: Students Per Grade
        $grades = Grade::query()
            ->when($stage_id, fn ($q) => $q->where('stage_id', $stage_id))
            ->when($grade_id, fn ($q) => $q->where('id', $grade_id))
            ->withCount(['users' => fn ($q) => $q->role('student')])
            ->get();

        $studentsPerGradeChart = [
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
                        'borderRadius' => 6,
                    ],
                ],
            ],
            'options' => [
                'animation' => false,
                'responsive' => true,
                'maintainAspectRatio' => false,
                'plugins' => [
                    'legend' => ['display' => false],
                ],
                'scales' => [
                    'x' => ['title' => ['display' => true, 'text' => __('lang.grade')]],
                    'y' => ['title' => ['display' => true, 'text' => __('lang.students')], 'beginAtZero' => true],
                ],
            ],
        ];

        // Chart 2: Student Status Distribution
        $statusActive = (clone $baseStudentQuery)->where('status', 'active')->count();
        $statusInactive = (clone $baseStudentQuery)->where('status', 'inactive')->count();
        $statusPending = (clone $baseStudentQuery)->where('status', 'pending')->count();

        $studentStatusChart = [
            'type' => 'doughnut',
            'data' => [
                'labels' => [__('lang.active'), __('lang.inactive'), __('lang.pending')],
                'datasets' => [
                    [
                        'data' => [$statusActive, $statusInactive, $statusPending],
                        'backgroundColor' => ['#22c55e', '#ef4444', '#f59e0b'],
                        'borderWidth' => 0,
                    ],
                ],
            ],
            'options' => [
                'animation' => false,
                'responsive' => true,
                'maintainAspectRatio' => false,
                'plugins' => [
                    'legend' => ['position' => 'bottom'],
                ],
            ],
        ];

        // Chart 3: Exam Scores Average
        $exams = (clone $examQuery)
            ->withAvg('attempts', 'total_score')
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();

        $examScoresChart = [
            'type' => 'bar',
            'data' => [
                'labels' => $exams->pluck('title')->toArray(),
                'datasets' => [
                    [
                        'label' => __('lang.average_score'),
                        'data' => $exams->map(fn ($e) => round($e->attempts_avg_total_score ?? 0, 1))->toArray(),
                        'backgroundColor' => '#25376F',
                        'borderRadius' => 6,
                    ],
                ],
            ],
            'options' => [
                'animation' => false,
                'responsive' => true,
                'maintainAspectRatio' => false,
                'plugins' => [
                    'legend' => ['display' => false],
                ],
                'scales' => [
                    'x' => ['title' => ['display' => true, 'text' => __('lang.exam')]],
                    'y' => ['title' => ['display' => true, 'text' => __('lang.average_score')], 'beginAtZero' => true, 'max' => 100],
                ],
            ],
        ];

        // Chart 4: New Students Monthly
        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = (clone $baseStudentQuery)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $months->push([
                'label' => $date->translatedFormat('M Y'),
                'count' => $count,
            ]);
        }

        $newStudentsMonthlyChart = [
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
                        'pointRadius' => 4,
                    ],
                ],
            ],
            'options' => [
                'animation' => false,
                'responsive' => true,
                'maintainAspectRatio' => false,
                'scales' => [
                    'x' => ['title' => ['display' => true, 'text' => __('lang.month') ?? 'الشهر']],
                    'y' => ['title' => ['display' => true, 'text' => __('lang.students') ?? 'الطلاب'], 'beginAtZero' => true],
                ],
            ],
        ];

        // Latest Students
        $latestStudents = (clone $baseStudentQuery)->with('grade.stage')->latest()->take(6)->get();

        return view('print.dashboard', compact(
            'totalStudents', 'activeStudents', 'inactiveStudents',
            'totalStages', 'totalGrades', 'totalSemesters', 'totalWeeks',
            'totalTrainings', 'totalExams', 'totalQuestions', 'totalAttempts',
            'avgScore', 'passRate',
            'studentsPerGradeChart', 'studentStatusChart', 'examScoresChart', 'newStudentsMonthlyChart',
            'latestStudents',
            'selectedStage', 'selectedGrade', 'selectedSection', 'selectedSemester'
        ));
    }

    public function students(Request $request)
    {
        $stage_id = $request->get('stage_id');
        $grade_id = $request->get('grade_id');
        $section_id = $request->get('section_id');
        $semester_id = $request->get('semester_id');
        $student_id = $request->get('student_id');

        $selectedStage = $stage_id ? (Stage::find($stage_id)?->name ?? null) : null;
        $selectedGrade = $grade_id ? (Grade::find($grade_id)?->name ?? null) : null;
        $selectedSection = $section_id ? (Section::find($section_id)?->name ?? null) : null;
        $selectedSemester = $semester_id ? (Semester::find($semester_id)?->name_with_academic_year ?? null) : null;
        $selectedStudent = $student_id ? (User::find($student_id)?->name ?? null) : null;

        $attemptQuery = ExamAttempt::query()
            ->when($stage_id, fn ($q) => $q->whereHas('exam.week.semester.grade', fn ($gq) => $gq->where('stage_id', $stage_id)))
            ->when($grade_id, fn ($q) => $q->whereHas('exam.week.semester', fn ($sq) => $sq->where('grade_id', $grade_id)))
            ->when($section_id, fn ($q) => $q->whereHas('user', fn ($uq) => $uq->where('section_id', $section_id)))
            ->when($semester_id, fn ($q) => $q->whereHas('exam.week', fn ($wq) => $wq->where('semester_id', $semester_id)))
            ->when($student_id, fn ($q) => $q->where('user_id', $student_id));

        $totalAttempts = (clone $attemptQuery)->count();
        $avgScore = round((clone $attemptQuery)->avg('total_score') ?? 0, 2);
        $passed = (clone $attemptQuery)->where('status', 'passed')->count();
        $failed = (clone $attemptQuery)->where('status', 'failed')->count();
        $passRate = $totalAttempts > 0 ? round(($passed / $totalAttempts) * 100) : 0;

        // Chart 1: Pass Fail Ratio Chart
        $passFailChart = [
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
            'options' => [
                'animation' => false,
                'responsive' => true,
                'maintainAspectRatio' => false,
                'plugins' => [
                    'legend' => ['position' => 'bottom'],
                ],
            ],
        ];

        // Chart 2: Performance Over Time Chart
        $monthlyPerformance = (clone $attemptQuery)
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

        $performanceChart = [
            'type' => 'line',
            'data' => [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => __('lang.average_score'),
                        'data' => $data,
                        'borderColor' => '#3b82f6',
                        'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                        'fill' => true,
                        'tension' => 0.4,
                        'pointBackgroundColor' => '#3b82f6',
                        'pointRadius' => 4,
                    ],
                ],
            ],
            'options' => [
                'animation' => false,
                'responsive' => true,
                'maintainAspectRatio' => false,
                'scales' => [
                    'x' => ['title' => ['display' => true, 'text' => __('lang.month') ?? 'الشهر']],
                    'y' => ['title' => ['display' => true, 'text' => __('lang.average_score')], 'beginAtZero' => true, 'max' => 100],
                ],
            ],
        ];

        // Students Tables
        $studentsBase = User::role('student')
            ->when($stage_id, fn ($q) => $q->whereHas('grade', fn ($gq) => $gq->where('stage_id', $stage_id)))
            ->when($grade_id, fn ($q) => $q->where('grade_id', $grade_id))
            ->when($section_id, fn ($q) => $q->where('section_id', $section_id))
            ->when($student_id, fn ($q) => $q->where('id', $student_id));

        $topStudents = (clone $studentsBase)
            ->withAvg(['examAttempts' => function ($q) use ($semester_id) {
                if ($semester_id) {
                    $q->whereHas('exam.week', fn ($wq) => $wq->where('semester_id', $semester_id));
                }
            }], 'total_score')
            ->orderByDesc('exam_attempts_avg_total_score')
            ->take(5)
            ->get();

        $weakStudents = (clone $studentsBase)
            ->withAvg(['examAttempts' => function ($q) use ($semester_id) {
                if ($semester_id) {
                    $q->whereHas('exam.week', fn ($wq) => $wq->where('semester_id', $semester_id));
                }
            }], 'total_score')
            ->having('exam_attempts_avg_total_score', '<', 60)
            ->having('exam_attempts_avg_total_score', '>', 0)
            ->orderBy('exam_attempts_avg_total_score')
            ->take(5)
            ->get();

        return view('print.students', compact(
            'totalAttempts', 'avgScore', 'passRate',
            'passFailChart', 'performanceChart',
            'topStudents', 'weakStudents',
            'selectedStage', 'selectedGrade', 'selectedSection', 'selectedSemester', 'selectedStudent'
        ));
    }

    public function exams(Request $request)
    {
        $stage_id = $request->get('stage_id');
        $grade_id = $request->get('grade_id');
        $section_id = $request->get('section_id');
        $semester_id = $request->get('semester_id');

        $selectedStage = $stage_id ? (Stage::find($stage_id)?->name ?? null) : null;
        $selectedGrade = $grade_id ? (Grade::find($grade_id)?->name ?? null) : null;
        $selectedSection = $section_id ? (Section::find($section_id)?->name ?? null) : null;
        $selectedSemester = $semester_id ? (Semester::find($semester_id)?->name_with_academic_year ?? null) : null;

        $examQuery = Exam::query()
            ->when($stage_id, fn ($q) => $q->whereHas('week.semester.grade', fn ($gq) => $gq->where('stage_id', $stage_id)))
            ->when($grade_id, fn ($q) => $q->whereHas('week.semester', fn ($sq) => $sq->where('grade_id', $grade_id)))
            ->when($semester_id, fn ($q) => $q->whereHas('week', fn ($wq) => $wq->where('semester_id', $semester_id)));

        $attemptQuery = ExamAttempt::query()
            ->when($stage_id, fn ($q) => $q->whereHas('exam.week.semester.grade', fn ($gq) => $gq->where('stage_id', $stage_id)))
            ->when($grade_id, fn ($q) => $q->whereHas('exam.week.semester', fn ($sq) => $sq->where('grade_id', $grade_id)))
            ->when($section_id, fn ($q) => $q->whereHas('user', fn ($uq) => $uq->where('section_id', $section_id)))
            ->when($semester_id, fn ($q) => $q->whereHas('exam.week', fn ($wq) => $wq->where('semester_id', $semester_id)));

        $totalExams = (clone $examQuery)->count();
        $totalAttempts = (clone $attemptQuery)->count();

        // Chart: Average Score (Top 10 Recent Exams)
        $exams = (clone $examQuery)->withAvg('attempts', 'total_score')
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();

        $averageScoreChart = [
            'type' => 'bar',
            'data' => [
                'labels' => $exams->pluck('title')->toArray(),
                'datasets' => [
                    [
                        'label' => __('lang.average_score'),
                        'data' => $exams->map(fn ($e) => round($e->attempts_avg_total_score ?? 0, 2))->toArray(),
                        'backgroundColor' => '#25376F',
                        'borderRadius' => 6,
                    ],
                ],
            ],
            'options' => [
                'animation' => false,
                'responsive' => true,
                'maintainAspectRatio' => false,
                'plugins' => [
                    'legend' => ['display' => false],
                ],
                'scales' => [
                    'x' => ['title' => ['display' => true, 'text' => __('lang.exam')]],
                    'y' => ['title' => ['display' => true, 'text' => __('lang.average_score')], 'beginAtZero' => true, 'max' => 100],
                ],
            ],
        ];

        // Tables
        $studentAnswerQuery = StudentAnswer::query();
        if ($stage_id || $grade_id || $semester_id || $section_id) {
            $studentAnswerQuery->whereHas('attempt', function ($q) use ($stage_id, $grade_id, $semester_id, $section_id) {
                if ($stage_id) {
                    $q->whereHas('exam.week.semester.grade', fn ($sq) => $sq->where('stage_id', $stage_id));
                }
                if ($grade_id) {
                    $q->whereHas('exam.week.semester', fn ($sq) => $sq->where('grade_id', $grade_id));
                }
                if ($semester_id) {
                    $q->whereHas('exam.week', fn ($sq) => $sq->where('semester_id', $semester_id));
                }
                if ($section_id) {
                    $q->whereHas('user', fn ($sq) => $sq->where('section_id', $section_id));
                }
            });
        }

        $hardestQuestions = (clone $studentAnswerQuery)
            ->select('question_id', DB::raw('SUM(is_correct) as correct_count'), DB::raw('COUNT(*) as total_attempts'))
            ->with('question.exam')
            ->groupBy('question_id')
            ->havingRaw('COUNT(*) > 0')
            ->orderByRaw('(SUM(is_correct) / COUNT(*)) ASC')
            ->take(5)
            ->get();

        $easiestQuestions = (clone $studentAnswerQuery)
            ->select('question_id', DB::raw('SUM(is_correct) as correct_count'), DB::raw('COUNT(*) as total_attempts'))
            ->with('question.exam')
            ->groupBy('question_id')
            ->havingRaw('COUNT(*) > 0')
            ->orderByRaw('(SUM(is_correct) / COUNT(*)) DESC')
            ->take(5)
            ->get();

        $difficultExams = (clone $examQuery)
            ->with('week.semester.grade.stage')
            ->withCount(['attempts as pass_count' => function ($query) {
                $query->where('status', 'passed');
            }])
            ->withCount('attempts')
            ->having('attempts_count', '>', 0)
            ->orderByRaw('pass_count / attempts_count ASC')
            ->take(5)
            ->get();

        return view('print.exams', compact(
            'totalExams', 'totalAttempts',
            'averageScoreChart',
            'hardestQuestions', 'easiestQuestions', 'difficultExams',
            'selectedStage', 'selectedGrade', 'selectedSection', 'selectedSemester'
        ));
    }
}
