<?php

namespace App\Livewire\Student;

use App\Models\Grade;
use App\Models\Semester;
use App\Models\Stage;
use App\Models\Training;
use App\Models\Week;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('my_trainings')]
#[Lazy]
class StudentTrainingsData extends Component
{
    use WithPagination;

    public string $search = '';

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

    public function updatedSearch()
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
            ['label' => __('lang.my_trainings') ?? 'تدريباتي', 'icon' => 'o-play-circle'],
        ];
    }

    public function render(): View
    {
        $user = Auth::user();
        
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

        $trainingsQuery = Training::whereHas('week', function ($query) use ($filteredGrades) {
            $query->whereHas('semester', function ($q) use ($filteredGrades) {
                $q->whereIn('grade_id', $filteredGrades->pluck('id'));
            });
        });

        if (! empty($this->selectedSemester)) {
            $trainingsQuery->whereHas('week', function ($q) {
                $q->where('semester_id', $this->selectedSemester);
            });
        }

        if (! empty($this->selectedWeek)) {
            $trainingsQuery->where('week_id', $this->selectedWeek);
        }

        $trainings = $trainingsQuery->with('week.semester')
            ->where('is_active', true)
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%'.$this->search.'%')
                    ->orWhere('description', 'like', '%'.$this->search.'%');
            })
            ->latest()
            ->paginate(12);

        return view('livewire.student.student-trainings-data', compact('trainings', 'stages', 'filteredGrades', 'semesters', 'weeks'));
    }
}
