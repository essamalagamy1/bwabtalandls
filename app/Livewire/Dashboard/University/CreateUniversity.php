<?php

namespace App\Livewire\Dashboard\University;

use App\Models\University;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class CreateUniversity extends Component
{
    use Toast, WithFileUploads;

    public bool $modalAdd = false;

    public $name_ar;

    public $name_en;

    public $image;

    public $status = 'inactive';

    public function render(): View
    {
        return view('livewire.dashboard.university.create-university');
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
        $this->authorize('create_university');
        $this->validate();
        $university = University::create([
            'name' => [
                'ar' => $this->name_ar,
                'en' => $this->name_en,
            ],
            'status' => $this->status,
        ]);
        if ($this->image) {
            $university->addMedia($this->image->getRealPath())->toMediaCollection('image');
        }
        $this->modalAdd = false;
        $this->dispatch('render')->component(UniversityData::class);
        $this->success(__('lang.added_successfully', ['attribute' => __('lang.university')]));
    }

    public function resetData(): void
    {
        $this->reset(['name_ar', 'name_en', 'image', 'status']);
        $this->status = 'inactive';
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
