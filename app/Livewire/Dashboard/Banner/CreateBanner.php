<?php

namespace App\Livewire\Dashboard\Banner;

use App\Models\Banner;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class CreateBanner extends Component
{
    use Toast, WithFileUploads;

    public bool $modalAdd = false;

    public $name_ar;

    public $name_en;

    public $description_ar;

    public $description_en;

    public $image;

    public $sort;

    public $status = 'inactive';

    public function render(): View
    {
        return view('livewire.dashboard.banner.create-banner');
    }

    public function rules(): array
    {
        return [
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'description_ar' => 'required|string|max:500',
            'description_en' => 'required|string|max:500',
            'image' => 'nullable|image|max:5000|mimes:jpg,jpeg,png,gif,webp,svg',
            'status' => 'required|in:active,inactive',
            'sort' => 'required|integer',
        ];
    }

    public function saveAdd(): void
    {
        $this->authorize('create_banner');
        $this->validate();
        $banner = Banner::create([
            'sort' => $this->sort,
            'name' => [
                'ar' => $this->name_ar,
                'en' => $this->name_en,
            ],
            'description' => [
                'ar' => $this->description_ar,
                'en' => $this->description_en,
            ],
            'status' => $this->status,
        ]);
        if ($this->image) {
            $banner->addMedia($this->image->getRealPath())->toMediaCollection('image');
        }
        $this->modalAdd = false;
        $this->dispatch('render')->component(BannerData::class);
        $this->success(__('lang.added_successfully', ['attribute' => __('lang.banner')]));
    }

    public function resetData(): void
    {
        $this->reset(['name_ar', 'name_en', 'description_ar', 'description_en', 'image', 'status']);
        $this->status = 'inactive';
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
