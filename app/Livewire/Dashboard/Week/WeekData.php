<?php

namespace App\Livewire\Dashboard\Week;

use App\Models\Exam;
use App\Models\Grade;
use App\Models\Semester;
use App\Models\Stage;
use App\Models\Training;
use App\Models\Week;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

#[Title('weeks')]
#[Lazy]
class WeekData extends Component
{
    use Toast, WithPagination;

    public function placeholder(): View
    {
        return view('livewire.placeholders.page-loading');
    }

    public $all_stages = [];

    public $all_grades = [];

    public $all_semesters = [];

    public $search_title;

    public $search_stage_id;

    public $search_grade_id;

    public $search_semester_id;

    public $search_is_active = '';

    public function mount(): void
    {
        $this->all_stages = Stage::where('is_active', true)->get(['id', 'name'])->toArray();
        $this->loadSemesters();
        view()->share('breadcrumbs', $this->breadcrumbs());
    }

    public function updatedSearchStageId(): void
    {
        $this->search_grade_id = null;
        $this->search_semester_id = null;
        
        if ($this->search_stage_id) {
            $this->all_grades = Grade::where('stage_id', $this->search_stage_id)
                ->where('is_active', true)
                ->get(['id', 'name'])
                ->toArray();
        } else {
            $this->all_grades = [];
        }
        $this->loadSemesters();
        $this->resetPage();
    }

    public function updatedSearchGradeId(): void
    {
        $this->search_semester_id = null;
        $this->loadSemesters();
        $this->resetPage();
    }

    private function loadSemesters(): void
    {
        $query = Semester::with('grade.stage')->where('is_active', true);
        
        if ($this->search_grade_id) {
            $query->where('grade_id', $this->search_grade_id);
        } elseif ($this->search_stage_id) {
            $query->whereHas('grade', function($q) {
                $q->where('stage_id', $this->search_stage_id);
            });
        }

        $this->all_semesters = $query->get()->map(function ($semester) {
            return [
                'id' => $semester->id,
                'name' => $semester->name_with_academic_year,
                'full_path_name' => ($semester->grade?->stage?->name ?? '').' - '.($semester->grade?->name ?? ''),
            ];
        })->toArray();
    }

    public function breadcrumbs(): array
    {
        return [
            ['label' => __('lang.weeks'), 'icon' => 'o-calendar-days'],
        ];
    }

    #[On('render')]
    public function render(): View
    {
        $data['weeks'] = Week::query()
            ->when($this->search_title, fn (Builder $q) => $q->where('title', 'like', "%{$this->search_title}%"))
            ->when($this->search_semester_id, fn (Builder $q) => $q->where('semester_id', $this->search_semester_id))
            ->when($this->search_grade_id, fn (Builder $q) => $q->whereHas('semester', fn($q2) => $q2->where('grade_id', $this->search_grade_id)))
            ->when($this->search_stage_id, fn (Builder $q) => $q->whereHas('semester.grade', fn($q2) => $q2->where('stage_id', $this->search_stage_id)))
            ->when($this->search_is_active !== '', fn (Builder $q) => $q->where('is_active', (bool) $this->search_is_active))
            ->with(['semester.grade.stage'])
            ->withCount(['trainings', 'exams'])
            ->orderBy('order')
            ->paginate(20);

        return view('livewire.dashboard.week.week-data', $data);
    }

    public function toggleActive($id): void
    {
        $this->authorize('edit_week');
        $week = Week::findOrFail($id);
        $newStatus = ! $week->is_active;
        $week->update(['is_active' => $newStatus]);

        if (! $newStatus) {
            Training::where('week_id', $week->id)->update(['is_active' => false]);
            Exam::where('week_id', $week->id)->update(['is_active' => false]);
        }

        $this->success(__('lang.updated_successfully', ['attribute' => __('lang.week')]));
        $this->dispatch('render')->component(WeekData::class);
    }

    public function delete($id): void
    {
        $this->authorize('delete_week');
        Week::findOrFail($id)->delete();
        $this->success(__('lang.deleted_successfully', ['attribute' => __('lang.week')]));
    }
}
