<?php

namespace App\Livewire\Dashboard\Week;

use App\Models\Week;
use Livewire\Component;
use Mary\Traits\Toast;

class CreateWeek extends Component
{
    use Toast;

    public bool $modalAdd = false;
    public $title;
    public $order;
    public $semester_id;
    public bool $is_active = true;
    public $start_date;
    public $end_date;
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
            'is_active'   => 'boolean',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
        ];
    }

    public function saveAdd(): void
    {
        $this->authorize('create_week');
        $this->validate();

        Week::create([
            'title'       => $this->title,
            'order'       => $this->order,
            'semester_id' => $this->semester_id,
            'is_active'   => $this->is_active,
            'start_date'  => $this->start_date,
            'end_date'    => $this->end_date,
        ]);

        $this->modalAdd = false;
        $this->dispatch('render')->component(WeekData::class);
        $this->success(__('lang.created_successfully', ['attribute' => __('lang.week')]));
    }

    public function resetData(): void
    {
        $this->reset(['title', 'order', 'semester_id', 'start_date', 'end_date']);
        $this->is_active = true;
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
