<?php

namespace App\Livewire\Dashboard\SubCategory;

use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

#[Title('subcategories')]
#[Lazy]
class SubCategoryData extends Component
{
    use Toast, WithPagination;

    public function placeholder(): View
    {
        return view('livewire.placeholders.page-loading');
    }

    public $all_subcategory;

    public $search_subcategory_id;

    #[Url]
    public $filter_category_id;

    public $filter_status;

    public $all_categories;

    public function mount(): void
    {
        $this->all_subcategory = Category::whereNotNull('parent_id')->get(['id', 'name'])->map(function ($type) {
            return [
                'id' => $type->id,
                'name' => $type->name,
            ];
        })->toArray();
        $this->all_categories = Category::whereNull('parent_id')->get(['id', 'name'])->map(function ($type) {
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
                'label' => __('lang.subcategories'),
                'icon' => 'o-rectangle-group',
            ],
        ];
    }

    #[On('render')]
    public function render(): View
    {
        $data['subcategories'] = Category::whereNotNull('parent_id')
            ->with('parent')
            ->when($this->search_subcategory_id, fn (Builder $query) => $query->where('id', $this->search_subcategory_id))
            ->when($this->filter_category_id, fn (Builder $query) => $query->where('parent_id', $this->filter_category_id))
            ->when($this->filter_status, fn (Builder $query) => $query->where('status', $this->filter_status))
            ->latest()
            ->paginate(20);

        return view('livewire.dashboard.sub-category.sub-category-data', $data);
    }

    public function delete($id): void
    {
        $this->authorize('delete_subcategory');
        $subcategory = Category::findOrFail($id);

        // Delete image using Media Library
        $subcategory->clearMediaCollection('image');

        $subcategory->delete();
        $this->success(__('lang.deleted_successfully', ['attribute' => __('lang.subcategory')]));
    }
}
