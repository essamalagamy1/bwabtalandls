<?php

namespace App\Livewire\Dashboard\Week;

use App\Models\Week;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class CreateWeek extends Component
{
    use Toast, WithFileUploads;

    public bool $modalAdd = false;
    public $title;
    public $order;
    public $semester_id;
    public $image;
    public $all_semesters;

    public function render()
    {
        return view('livewire.dashboard.week.create-week');
    }

    public function rules(): array
    {
        return [
            'title'       => 'required|string|max:255',
            'order'       => 'required|integer|min:1',
            'semester_id' => 'required|exists:semesters,id',
            'image'       => 'nullable|image|max:5000|mimes:jpg,jpeg,png,gif,webp,svg',
        ];
    }

    public function saveAdd(): void
    {
        $this->authorize('create_week');
        $this->validate();

        $week = Week::create([
            'title'       => $this->title,
            'order'       => $this->order,
            'semester_id' => $this->semester_id,
        ]);

        if ($this->image) {
            $week->addMedia($this->image->getRealPath())->toMediaCollection('image');
        }

        $this->modalAdd = false;
        $this->dispatch('render')->component(WeekData::class);
        $this->success(__('lang.created_successfully', ['attribute' => __('lang.week')]));
    }

    public function resetData(): void
    {
        $this->reset(['title', 'order', 'semester_id', 'image']);
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
