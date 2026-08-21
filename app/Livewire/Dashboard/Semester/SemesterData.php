<?php

namespace App\Livewire\Dashboard\Semester;

use App\Models\Grade;
use App\Models\Semester;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

#[Title('semesters')]
#[Lazy]
class SemesterData extends Component
{
    use Toast, WithPagination;

    public function placeholder(): View
    {
        return view('livewire.placeholders.page-loading');
    }

    public $all_stages = [];
    public $search_stage_id;
    public $all_grades;
    public $search_name;
    public $search_grade_id;
    public $search_is_active = '';

    public function mount(): void
    {
        $this->all_stages = \App\Models\Stage::where('is_active', true)->get(['id', 'name'])->toArray();
        $this->loadGrades();
        view()->share('breadcrumbs', $this->breadcrumbs());
    }

    public function loadGrades(): void
    {
        $this->all_grades = Grade::with('stage:id,name')->where('is_active', true)
            ->when($this->search_stage_id, fn($q) => $q->where('stage_id', $this->search_stage_id))
            ->get(['id', 'name', 'stage_id'])
            ->map(function ($grade) {
                return [
                    'id' => $grade->id,
                    'name' => $grade->name,
                    'full_path_name' => $grade->stage?->name ?? ''
                ];
            })->toArray();
    }

    public function updatedSearchStageId(): void
    {
        $this->search_grade_id = null;
        $this->loadGrades();
    }

    public function breadcrumbs(): array
    {
        return [
            ['label' => __('lang.semesters'), 'icon' => 'o-calendar'],
        ];
    }

    #[On('render')]
    public function render(): View
    {
        $data['semesters'] = Semester::query()
            ->when($this->search_name, fn(Builder $q) => $q->where('name', 'like', "%{$this->search_name}%"))
            ->when($this->search_stage_id && !$this->search_grade_id, fn(Builder $q) => $q->whereHas('grade', fn($gq) => $gq->where('stage_id', $this->search_stage_id)))
            ->when($this->search_grade_id, fn(Builder $q) => $q->where('grade_id', $this->search_grade_id))
            ->when($this->search_is_active !== '', fn(Builder $q) => $q->where('is_active', (bool)$this->search_is_active))
            ->with(['grade.stage'])
            ->withCount('weeks')
            ->latest()
            ->paginate(10);

        return view('livewire.dashboard.semester.semester-data', $data);
    }

    public function toggleActive($id): void
    {
        $this->authorize('edit_semester');
        $semester = Semester::findOrFail($id);
        $newStatus = !$semester->is_active;

        if ($newStatus) {
            $exists = Semester::where('grade_id', $semester->grade_id)
                ->where('is_active', true)
                ->where('id', '!=', $semester->id)
                ->exists();
            if ($exists) {
                $this->error('لا يمكن تفعيل هذا الفصل لوجود فصل آخر مفعل لنفس الصف.');
                return;
            }
        }

        $semester->update(['is_active' => $newStatus]);
        
        if (!$newStatus) {
            $weekIds = $semester->weeks()->pluck('id');
            $semester->weeks()->update(['is_active' => false]);
            
            if ($weekIds->isNotEmpty()) {
                \App\Models\Training::whereIn('week_id', $weekIds)->update(['is_active' => false]);
                \App\Models\Exam::whereIn('week_id', $weekIds)->update(['is_active' => false]);
            }
        }

        $this->success(__('lang.updated_successfully', ['attribute' => __('lang.semester')]));
        $this->dispatch('render')->component(SemesterData::class);
    }

    public function delete($id): void
    {
        $this->authorize('delete_semester');
        Semester::findOrFail($id)->delete();
        $this->success(__('lang.deleted_successfully', ['attribute' => __('lang.semester')]));
    }
}
