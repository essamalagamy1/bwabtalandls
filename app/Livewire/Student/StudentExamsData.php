<?php

namespace App\Livewire\Student;

use App\Models\Exam;
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

        $examsQuery = Exam::whereHas('week', function ($query) use ($gradeId) {
            $query->whereHas('semester', function ($q) use ($gradeId) {
                $q->where('grade_id', $gradeId);
            });
        });

        if (!empty($this->selectedSemester)) {
            $examsQuery->whereHas('week', function($q) {
                $q->where('semester_id', $this->selectedSemester);
            });
        }

        if (!empty($this->selectedWeek)) {
            $examsQuery->where('week_id', $this->selectedWeek);
        }

        $exams = $examsQuery->with(['week.semester', 'attempts' => function($q) use ($user) {
            $q->where('user_id', $user->id);
        }])
        ->where('is_active', true)
        ->withCount('questions')
        ->latest()
        ->paginate(12);

        return view('livewire.student.student-exams-data', compact('exams', 'semesters', 'weeks'));
    }
}
