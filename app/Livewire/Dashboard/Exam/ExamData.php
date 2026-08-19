<?php

namespace App\Livewire\Dashboard\Exam;

use App\Models\Exam;
use App\Models\Week;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

#[Title('exams')]
#[Lazy]
class ExamData extends Component
{
    use Toast, WithPagination;

    public function placeholder(): View
    {
        return view('livewire.placeholders.page-loading');
    }

    public $all_stages;
    public $all_grades;
    public $all_semesters;
    public $all_weeks;
    public $search_title;
    
    #[Url]
    public $search_stage_id;
    #[Url]
    public $search_grade_id;
    #[Url]
    public $search_semester_id;
    #[Url]
    public $search_week_id;
    
    public $search_is_active = '';

    public function updatedSearchStageId($value)
    {
        $this->search_grade_id = null;
        $this->search_semester_id = null;
        $this->search_week_id = null;
        $this->loadGrades();
        $this->loadSemesters();
        $this->loadWeeks();
    }

    public function updatedSearchGradeId($value)
    {
        $this->search_semester_id = null;
        $this->search_week_id = null;
        $this->loadSemesters();
        $this->loadWeeks();
    }

    public function updatedSearchSemesterId($value)
    {
        $this->search_week_id = null;
        $this->loadWeeks();
    }

    public function loadStages() {
        $this->all_stages = \App\Models\Stage::where('is_active', true)->get(['id', 'name'])->toArray();
    }

    public function loadGrades() {
        $query = \App\Models\Grade::with('stage:id,name')->where('is_active', true);
        if ($this->search_stage_id) {
            $query->where('stage_id', $this->search_stage_id);
        }
        $this->all_grades = $query->get(['id', 'name', 'stage_id'])
            ->map(function ($grade) {
                return [
                    'id' => $grade->id,
                    'name' => $grade->name,
                    'full_path_name' => $grade->stage?->name ?? ''
                ];
            })->toArray();
    }

    public function loadSemesters() {
        $query = \App\Models\Semester::with('grade.stage')->where('is_active', true);
        if ($this->search_grade_id) {
            $query->where('grade_id', $this->search_grade_id);
        } elseif ($this->search_stage_id) {
            $query->whereHas('grade', fn($q) => $q->where('stage_id', $this->search_stage_id));
        }
        $this->all_semesters = $query->get(['id', 'name', 'grade_id'])
            ->map(function ($semester) {
                return [
                    'id' => $semester->id,
                    'name' => $semester->name,
                    'full_path_name' => ($semester->grade?->stage?->name ?? '') . ' - ' . ($semester->grade?->name ?? '')
                ];
            })->toArray();
    }

    public function loadWeeks() {
        $query = Week::with('semester.grade.stage')
            ->where('is_active', true)
            ->whereHas('semester', function ($q) {
                $q->where('is_active', true)
                  ->whereHas('grade', function ($q2) {
                      $q2->where('is_active', true)
                         ->whereHas('stage', function ($q3) {
                             $q3->where('is_active', true);
                         });
                  });
            });

        if ($this->search_semester_id) {
            $query->where('semester_id', $this->search_semester_id);
        } elseif ($this->search_grade_id) {
            $query->whereHas('semester', fn($q) => $q->where('grade_id', $this->search_grade_id));
        } elseif ($this->search_stage_id) {
            $query->whereHas('semester.grade', fn($q) => $q->where('stage_id', $this->search_stage_id));
        }
        
        $this->all_weeks = $query->get(['id', 'title as name', 'semester_id'])
            ->map(function ($week) {
                return [
                    'id' => $week->id,
                    'name' => $week->name,
                    'full_path_name' => ($week->semester?->grade?->stage?->name ?? '') . ' - ' . ($week->semester?->grade?->name ?? '') . ' - ' . ($week->semester?->name ?? '')
                ];
            })
            ->toArray();
    }

    public function mount(): void
    {
        $this->loadStages();
        $this->loadGrades();
        $this->loadSemesters();
        $this->loadWeeks();
        view()->share('breadcrumbs', $this->breadcrumbs());
    }

    public function breadcrumbs(): array
    {
        return [
            ['label' => __('lang.exams'), 'icon' => 'o-document-text'],
        ];
    }

    #[On('render')]
    public function render(): View
    {
        $data['exams'] = Exam::query()
            ->when($this->search_title, fn(Builder $q) => $q->where('title', 'like', "%{$this->search_title}%"))
            ->when($this->search_stage_id, fn(Builder $q) => $q->whereHas('week.semester.grade.stage', fn($q2) => $q2->where('id', $this->search_stage_id)))
            ->when($this->search_grade_id, fn(Builder $q) => $q->whereHas('week.semester.grade', fn($q2) => $q2->where('id', $this->search_grade_id)))
            ->when($this->search_semester_id, fn(Builder $q) => $q->whereHas('week.semester', fn($q2) => $q2->where('id', $this->search_semester_id)))
            ->when($this->search_week_id, fn(Builder $q) => $q->where('week_id', $this->search_week_id))
            ->when($this->search_is_active !== '', fn(Builder $q) => $q->where('is_active', (bool)$this->search_is_active))
            ->with(['week.semester'])
            ->withCount('questions')
            ->latest()
            ->paginate(10);

        return view('livewire.dashboard.exam.exam-data', $data);
    }

    public function delete($id): void
    {
        $this->authorize('delete_exam');
        Exam::findOrFail($id)->delete();
        $this->success(__('lang.deleted_successfully', ['attribute' => __('lang.exam')]));
    }

    public function toggleActive($id): void
    {
        $this->authorize('edit_exam');
        $exam = Exam::findOrFail($id);
        $exam->update(['is_active' => !$exam->is_active]);

        if ($exam->is_active) {
            $gradeId = \App\Models\Semester::find($exam->semester_id)?->grade_id;
            if ($gradeId) {
                \App\Jobs\NotifyStudentsOfNewContentJob::dispatch($gradeId, $exam->title, 'exam');
            }
        }

        $this->success(__('lang.updated_successfully', ['attribute' => __('lang.exam')]));
    }
}
