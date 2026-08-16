<?php

namespace App\Livewire\Dashboard\Category;

use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

#[Title('categories')]
#[Lazy]
class CategoryData extends Component
{
    use Toast, WithPagination;

    public function placeholder(): View
    {
        return view('livewire.placeholders.page-loading');
    }

    public $all_category;

    public $search_category_id;

    public $filter_status;

    public function mount(): void
    {
        $this->all_category = Category::whereNull('parent_id')->get(['id', 'name'])->map(function ($type) {
            return [
                'id' => $type->id,
                'name' => $type->name,
            ];
        })->toArray();
        view()->share('breadcrumbs', $this->breadcrumbs());
    }

    public function breadcrumbs(): array
    {
        return [
            [
                'label' => __('lang.categories'),
                'icon' => 'o-squares-plus',
            ],
        ];
    }

    #[On('render')]
    public function render(): View
    {
        $data['categories'] = Category::whereNull('parent_id')
            ->withCount('children')
            ->when($this->search_category_id, fn (Builder $query) => $query->where('id', $this->search_category_id))
            ->when($this->filter_status, fn (Builder $query) => $query->where('status', $this->filter_status))
            ->latest()
            ->paginate(20);

        return view('livewire.dashboard.category.category-data', $data);
    }

    public function delete($id): void
    {
        $this->authorize('delete_category');
        $category = Category::findOrFail($id);

        // Delete image using Media Library
        $category->clearMediaCollection('image');

        $category->delete();
        $this->success(__('lang.deleted_successfully', ['attribute' => __('lang.category')]));
    }
}
