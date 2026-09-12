<?php

namespace App\Livewire\Student;

use App\Models\Exam;
use App\Models\Grade;
use App\Models\Semester;
use App\Models\Stage;
use App\Models\Week;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('my_exams')]
#[Lazy]
class StudentExamsData extends Component
{
    use WithPagination;

    public $selectedStage = '';

    public $selectedGrade = '';

    public $selectedSemester = '';

    public $selectedWeek = '';

    public function updatedSelectedStage()
    {
        $this->selectedGrade = '';
        $this->selectedSemester = '';
        $this->selectedWeek = '';
        $this->resetPage();
    }

    public function updatedSelectedGrade()
    {
        $this->selectedSemester = '';
        $this->selectedWeek = '';
        $this->resetPage();
    }

    public function updatedSelectedSemester()
    {
        $this->selectedWeek = '';
        $this->resetPage();
    }

    public function updatedSelectedWeek()
    {
        $this->resetPage();
    }

    public function placeholder(): View
    {
        return view('livewire.placeholders.page-loading');
    }

    public function mount(): void
    {
        view()->share('breadcrumbs', $this->breadcrumbs());
    }

    public function breadcrumbs(): array
    {
        return [
            ['label' => __('lang.my_exams'), 'icon' => 'o-document-text'],
        ];
    }

    public function render(): View
    {
        $user = Auth::user();
        
        // Get all historical and current grades
        $enrolledGradeIds = $user->enrollments()->pluck('grade_id')->toArray();
        if (empty($enrolledGradeIds)) {
            $enrolledGradeIds = [$user->grade_id];
        }

        // Fetch grades and their stages
        $grades = Grade::with('stage')->whereIn('id', $enrolledGradeIds)->where('is_active', true)->get();
        
        $stageIds = $grades->pluck('stage_id')->unique()->toArray();
        $stages = Stage::whereIn('id', $stageIds)->where('is_active', true)->get();

        $filteredGrades = collect();
        if (!empty($this->selectedStage)) {
            $filteredGrades = $grades->where('stage_id', $this->selectedStage);
        } else {
            $filteredGrades = $grades;
        }

        $semesters = collect();
        if (!empty($this->selectedGrade)) {
            $semesters = Semester::where('grade_id', $this->selectedGrade)
                ->where('is_active', true)
                ->get();
        } else {
            $semesters = Semester::whereIn('grade_id', $filteredGrades->pluck('id'))
                ->where('is_active', true)
                ->get();
        }

        if (empty($this->selectedSemester) && $semesters->isNotEmpty()) {
            $this->selectedSemester = $semesters->first()->id;
        }

        $weeks = [];
        if (! empty($this->selectedSemester)) {
            $weeks = Week::where('semester_id', $this->selectedSemester)
                ->where('is_active', true)
                ->get();
        } else {
            $weeks = Week::whereIn('semester_id', $semesters->pluck('id'))
                ->where('is_active', true)
                ->get();
        }

        $examsQuery = Exam::whereHas('week', function ($query) use ($filteredGrades) {
            $query->whereHas('semester', function ($q) use ($filteredGrades) {
                $q->whereIn('grade_id', $filteredGrades->pluck('id'));
            });
        });

        if (! empty($this->selectedSemester)) {
            $examsQuery->whereHas('week', function ($q) {
                $q->where('semester_id', $this->selectedSemester);
            });
        }

        if (! empty($this->selectedWeek)) {
            $examsQuery->where('week_id', $this->selectedWeek);
        }

        $exams = $examsQuery->with(['week.semester', 'attempts' => function ($q) use ($user) {
            $q->where('user_id', $user->id);
        }])
            ->where('is_active', true)
            ->withCount('questions')
            ->latest()
            ->paginate(12);

        return view('livewire.student.student-exams-data', compact('exams', 'stages', 'filteredGrades', 'semesters', 'weeks'));
    }
}
