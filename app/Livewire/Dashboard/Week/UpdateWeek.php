<?php

namespace App\Livewire\Dashboard\Week;

use App\Models\Week;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class UpdateWeek extends Component
{
    use Toast, WithFileUploads;

    public bool $modalUpdate = false;
    public Week $week;
    public $title;
    public $order;
    public $semester_id;
    public $image;
    public $all_semesters;

    public function mount(): void
    {
        $this->title       = $this->week->title;
        $this->order       = $this->week->order;
        $this->semester_id = $this->week->semester_id;
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

    public function saveUpdate(): void
    {
        $this->authorize('edit_week');
        $this->validate();

        $this->week->update([
            'title'       => $this->title,
            'order'       => $this->order,
            'semester_id' => $this->semester_id,
        ]);

        if ($this->image) {
            $this->week->addMedia($this->image->getRealPath())->toMediaCollection('image');
        }

        $this->modalUpdate = false;
        $this->dispatch('render')->component(WeekData::class);
        $this->success(__('lang.updated_successfully', ['attribute' => __('lang.week')]));
    }

    public function render(): View
    {
        return view('livewire.dashboard.week.update-week');
    }

    public function resetError(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
