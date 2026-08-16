<?php

namespace App\Livewire\Dashboard\Category;

use App\Models\Category;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class CreateCategory extends Component
{
    use Toast, WithFileUploads;

    public bool $modalAdd = false;

    public $name_ar;

    public $name_en;

    public $image;

    public $status = 'inactive';

    public function render(): View
    {
        return view('livewire.dashboard.category.create-category');
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

    public function saveAdd(): void
    {
        $this->authorize('create_category');
        $this->validate();
        $category = Category::create([
            'name' => [
                'ar' => $this->name_ar,
                'en' => $this->name_en,
            ],
            'status' => $this->status,
            'parent_id' => null,
        ]);
        if ($this->image) {
            $category->addMedia($this->image->getRealPath())->toMediaCollection('image');
        }
        $this->modalAdd = false;
        $this->dispatch('render')->component(CategoryData::class);
        $this->success(__('lang.added_successfully', ['attribute' => __('lang.category')]));
    }

    public function resetData(): void
    {
        $this->reset(['name_ar', 'name_en', 'image', 'status']);
        $this->status = 'inactive';
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
