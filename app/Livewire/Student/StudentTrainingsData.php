<?php

namespace App\Livewire\Student;

use App\Models\Training;
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
    public $selectedSemester = '';
    public $selectedWeek = '';

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
        $gradeId = $user->grade_id;

        $semesters = \App\Models\Semester::where('grade_id', $gradeId)
            ->where('is_active', true)
            ->get();

        if (empty($this->selectedSemester) && $semesters->isNotEmpty()) {
            $this->selectedSemester = $semesters->first()->id;
        }

        $weeks = [];
        if (!empty($this->selectedSemester)) {
            $weeks = \App\Models\Week::where('semester_id', $this->selectedSemester)
                ->where('is_active', true)
                ->get();
        }

        $trainingsQuery = Training::whereHas('week', function ($query) use ($gradeId) {
            $query->whereHas('semester', function ($q) use ($gradeId) {
                $q->where('grade_id', $gradeId);
            });
        });

        if (!empty($this->selectedSemester)) {
            $trainingsQuery->whereHas('week', function($q) {
                $q->where('semester_id', $this->selectedSemester);
            });
        }

        if (!empty($this->selectedWeek)) {
            $trainingsQuery->where('week_id', $this->selectedWeek);
        }

        $trainings = $trainingsQuery->with('week.semester')
        ->where('is_active', true)
        ->when($this->search, function ($query) {
            $query->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
        })
        ->latest()
        ->paginate(12);

        return view('livewire.student.student-trainings-data', compact('trainings', 'semesters', 'weeks'));
    }
}
