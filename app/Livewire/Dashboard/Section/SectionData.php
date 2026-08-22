<?php

namespace App\Livewire\Dashboard\Section;

use App\Models\Grade;
use App\Models\Section;
use App\Models\Stage;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

#[Title('sections')]
#[Lazy]
class SectionData extends Component
{
    use Toast, WithPagination;

    public function placeholder(): View
    {
        return view('livewire.placeholders.page-loading');
    }

    public $all_stages = [];
    public $search_stage_id;
    public $all_grades = [];
    public $search_name;
    public $search_grade_id;
    public $search_is_active = '';

    public function mount(): void
    {
        $this->all_stages = Stage::where('is_active', true)->get(['id', 'name'])->toArray();
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
            ['label' => __('lang.sections'), 'icon' => 'o-user-group'],
        ];
    }

    #[On('render')]
    public function render(): View
    {
        $data['sections'] = Section::query()
            ->when($this->search_name, fn(Builder $q) => $q->where('name', 'like', "%{$this->search_name}%"))
            ->when($this->search_stage_id && !$this->search_grade_id, fn(Builder $q) => $q->whereHas('grade', fn($gq) => $gq->where('stage_id', $this->search_stage_id)))
            ->when($this->search_grade_id, fn(Builder $q) => $q->where('grade_id', $this->search_grade_id))
            ->when($this->search_is_active !== '', fn(Builder $q) => $q->where('is_active', (bool)$this->search_is_active))
            ->with(['grade.stage'])
            ->latest()
            ->paginate(10);

        return view('livewire.dashboard.section.section-data', $data);
    }

    public function toggleActive($id): void
    {
        $this->authorize('edit_section');
        $section = Section::findOrFail($id);
        $section->update(['is_active' => !$section->is_active]);

        $this->success(__('lang.updated_successfully', ['attribute' => __('lang.section')]));
        $this->dispatch('render')->component(SectionData::class);
    }

    public function delete($id): void
    {
        $this->authorize('delete_section');
        Section::findOrFail($id)->delete();
        $this->success(__('lang.deleted_successfully', ['attribute' => __('lang.section')]));
    }
}
