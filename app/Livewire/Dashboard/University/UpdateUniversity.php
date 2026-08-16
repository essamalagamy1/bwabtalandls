<?php

namespace App\Livewire\Dashboard\University;

use App\Models\University;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class UpdateUniversity extends Component
{
    use Toast, WithFileUploads;

    public bool $modalUpdate = false;

    public University $university;

    public $name_ar;

    public $name_en;

    public $image;

    public $status;

    public function mount(): void
    {
        $this->name_ar = $this->university->getTranslation('name', 'ar');
        $this->name_en = $this->university->getTranslation('name', 'en');
        $this->status = $this->university->status->value;
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

    public function render(): View
    {
        return view('livewire.dashboard.university.update-university');
    }

    public function saveEdit(): void
    {
        $this->authorize('edit_university');
        $this->validate();
        $this->university->update([
            'name' => [
                'ar' => $this->name_ar,
                'en' => $this->name_en,
            ],
            'status' => $this->status,
        ]);
        if ($this->image) {
            $this->university->addMedia($this->image->getRealPath())->toMediaCollection('image');
        }
        $this->modalUpdate = false;
        $this->dispatch('render')->component(UniversityData::class);
        $this->success(__('lang.updated_successfully', ['attribute' => __('lang.university')]));
    }
}
