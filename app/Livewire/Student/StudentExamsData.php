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

    public $selectedWeek = '';

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

        $activeSemester = \App\Models\Semester::where('grade_id', $gradeId)
            ->where('is_active', true)
            ->first();

        $weeks = [];
        if ($activeSemester) {
            $weeks = \App\Models\Week::where('semester_id', $activeSemester->id)->get();
        }

        $examsQuery = Exam::whereHas('week', function ($query) use ($gradeId) {
            $query->whereHas('semester', function ($q) use ($gradeId) {
                $q->where('grade_id', $gradeId);
            });
        });

        if (!empty($this->selectedWeek)) {
            $examsQuery->where('week_id', $this->selectedWeek);
        }

        $exams = $examsQuery->with(['week.semester', 'attempts' => function($q) use ($user) {
            $q->where('user_id', $user->id);
        }])
        ->withCount('questions')
        ->latest()
        ->paginate(12);

        return view('livewire.student.student-exams-data', compact('exams', 'weeks'));
    }
}
