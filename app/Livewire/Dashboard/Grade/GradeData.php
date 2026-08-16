<?php

namespace App\Livewire\Dashboard\Grade;

use App\Models\Grade;
use App\Models\Stage;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

#[Title('grades')]
#[Lazy]
class GradeData extends Component
{
    use Toast, WithPagination;

    public function placeholder(): View
    {
        return view('livewire.placeholders.page-loading');
    }

    public $all_stages;
    public $search_name;
    public $search_stage_id;
    public $search_is_active = '';

    public function mount(): void
    {
        $this->all_stages = Stage::where('is_active', true)->get(['id', 'name'])->toArray();
        view()->share('breadcrumbs', $this->breadcrumbs());
    }

    public function breadcrumbs(): array
    {
        return [
            ['label' => __('lang.grades'), 'icon' => 'o-rectangle-group'],
        ];
    }

    #[On('render')]
    public function render(): View
    {
        $data['grades'] = Grade::query()
            ->when($this->search_name, fn(Builder $q) => $q->where('name', 'like', "%{$this->search_name}%"))
            ->when($this->search_stage_id, fn(Builder $q) => $q->where('stage_id', $this->search_stage_id))
            ->when($this->search_is_active !== '', fn(Builder $q) => $q->where('is_active', (bool)$this->search_is_active))
            ->with('stage')
            ->withCount('semesters')
            ->latest()
            ->paginate(10);

        return view('livewire.dashboard.grade.grade-data', $data);
    }

    public function toggleActive($id): void
    {
        $this->authorize('edit_grade');
        $grade = Grade::findOrFail($id);
        $grade->update(['is_active' => !$grade->is_active]);
        $this->success(__('lang.updated_successfully', ['attribute' => __('lang.grade')]));
        $this->dispatch('render')->component(GradeData::class);
    }

    public function delete($id): void
    {
        $this->authorize('delete_grade');
        Grade::findOrFail($id)->delete();
        $this->success(__('lang.deleted_successfully', ['attribute' => __('lang.grade')]));
    }
}
