<?php

namespace App\Livewire\Dashboard\Stage;

use App\Models\Stage;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

#[Title('stages')]
#[Lazy]
class StageData extends Component
{
    use Toast, WithPagination;

    public function placeholder(): View
    {
        return view('livewire.placeholders.page-loading');
    }

    public $search_name;
    public $search_is_active = '';

    public function mount(): void
    {
        view()->share('breadcrumbs', $this->breadcrumbs());
    }

    public function breadcrumbs(): array
    {
        return [
            ['label' => __('lang.stages'), 'icon' => 'o-academic-cap'],
        ];
    }

    #[On('render')]
    public function render(): View
    {
        $data['stages'] = Stage::query()
            ->when($this->search_name, fn(Builder $q) => $q->where('name', 'like', "%{$this->search_name}%"))
            ->when($this->search_is_active !== '', fn(Builder $q) => $q->where('is_active', (bool)$this->search_is_active))
            ->withCount('grades')
            ->latest()
            ->paginate(10);

        return view('livewire.dashboard.stage.stage-data', $data);
    }

    public function toggleActive($id): void
    {
        $this->authorize('edit_stage');
        $stage = Stage::findOrFail($id);
        $stage->update(['is_active' => !$stage->is_active]);
        $this->success(__('lang.updated_successfully', ['attribute' => __('lang.stage')]));
        $this->dispatch('render')->component(StageData::class);
    }

    public function delete($id): void
    {
        $this->authorize('delete_stage');
        Stage::findOrFail($id)->delete();
        $this->success(__('lang.deleted_successfully', ['attribute' => __('lang.stage')]));
    }
}
