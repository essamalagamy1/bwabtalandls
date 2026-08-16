<?php

namespace App\Livewire\Dashboard\Training;

use App\Models\Training;
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

#[Title('trainings')]
#[Lazy]
class TrainingData extends Component
{
    use Toast, WithPagination;

    public function placeholder(): View
    {
        return view('livewire.placeholders.page-loading');
    }

    public $all_weeks;
    public $search_title;
    #[Url]
    public $search_week_id;
    public $search_type;
    public $search_is_published = '';

    public function mount(): void
    {
        $this->all_weeks = Week::get(['id', 'title as name'])->toArray();
        view()->share('breadcrumbs', $this->breadcrumbs());
    }

    public function breadcrumbs(): array
    {
        return [
            ['label' => __('lang.trainings'), 'icon' => 'o-play-circle'],
        ];
    }

    #[On('render')]
    public function render(): View
    {
        $data['trainings'] = Training::query()
            ->when($this->search_title, fn(Builder $q) => $q->where('title', 'like', "%{$this->search_title}%"))
            ->when($this->search_week_id, fn(Builder $q) => $q->where('week_id', $this->search_week_id))
            ->when($this->search_type, fn(Builder $q) => $q->where('type', $this->search_type))
            ->when($this->search_is_published !== '', fn(Builder $q) => $q->where('is_published', (bool)$this->search_is_published))
            ->with('week')
            ->latest()
            ->paginate(10);

        return view('livewire.dashboard.training.training-data', $data);
    }

    public function delete($id): void
    {
        $this->authorize('delete_training');
        Training::findOrFail($id)->delete();
        $this->success(__('lang.deleted_successfully', ['attribute' => __('lang.training')]));
    }
}
