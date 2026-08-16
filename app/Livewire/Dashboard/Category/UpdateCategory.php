<?php

namespace App\Livewire\Dashboard\Category;

use App\Models\Category;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class UpdateCategory extends Component
{
    use Toast, WithFileUploads;

    public bool $modalUpdate = false;

    public Category $category;

    public $name_ar;

    public $name_en;

    public $image;

    public $status;

    public function mount(): void
    {
        $this->name_ar = $this->category->getTranslation('name', 'ar');
        $this->name_en = $this->category->getTranslation('name', 'en');
        $this->status = $this->category->status->value;
    }

    public function rules(): array
    {
        return [
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'image' => 'nullable|image|max:5000|mimes:jpg,jpeg,png,gif,webp,svg',
            'status' => 'required|in:active,inactive',
        ];
    }

    public function saveUpdate(): void
    {
        $this->authorize('edit_category');
        $this->validate();
        $this->category->update([
            'name' => [
                'ar' => $this->name_ar,
                'en' => $this->name_en,
            ],
            'status' => $this->status,
        ]);
        if ($this->image) {
            $this->category->addMedia($this->image->getRealPath())->toMediaCollection('image');
        }
        $this->modalUpdate = false;
        $this->dispatch('render')->component(CategoryData::class);
        $this->success(__('lang.updated_successfully', ['attribute' => __('lang.category')]));
    }

    public function render(): View
    {
        return view('livewire.dashboard.category.update-category');
    }

    public function resetError(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
