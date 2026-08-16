<?php

namespace App\Livewire\Dashboard\SubCategory;

use App\Models\Category;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class UpdateSubCategory extends Component
{
    use Toast, WithFileUploads;

    public bool $modalUpdate = false;

    public Category $subcategory;

    public $name_ar;

    public $name_en;

    public $image;

    public $parent_id;

    public $status;

    public $all_categories;

    public function mount(): void
    {
        $this->name_ar = $this->subcategory->getTranslation('name', 'ar');
        $this->name_en = $this->subcategory->getTranslation('name', 'en');
        $this->parent_id = $this->subcategory->parent_id;
        $this->status = $this->subcategory->status->value;
    }

    public function rules(): array
    {
        return [
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'parent_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:5000|mimes:jpg,jpeg,png,gif,webp,svg',
            'status' => 'required|in:active,inactive',
        ];
    }

    public function saveUpdate(): void
    {
        $this->authorize('edit_subcategory');
        $this->validate();
        $this->subcategory->update([
            'name' => [
                'ar' => $this->name_ar,
                'en' => $this->name_en,
            ],
            'parent_id' => $this->parent_id,
            'status' => $this->status,
        ]);
        if ($this->image) {
            $this->subcategory->addMedia($this->image->getRealPath())->toMediaCollection('image');
        }

        $this->modalUpdate = false;
        $this->dispatch('render')->component(SubCategoryData::class);
        $this->success(__('lang.updated_successfully', ['attribute' => __('lang.subcategory')]));
    }

    public function render(): View
    {
        return view('livewire.dashboard.sub-category.update-sub-category');
    }

    public function resetError(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
