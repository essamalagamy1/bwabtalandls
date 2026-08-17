<?php

namespace App\Livewire\Dashboard\Grade;

use App\Models\Grade;
use Livewire\Component;
use Mary\Traits\Toast;

class CreateGrade extends Component
{
    use Toast;

    public bool $modalAdd = false;
    public $name;
    public $stage_id;
    public bool $is_active = true;
    public $all_stages;

    public function render()
    {
        return view('livewire.dashboard.grade.create-grade');
    }

    public function rules(): array
    {
        return [
            'name'      => 'required|string|max:255|unique:grades,name',
            'stage_id'  => 'required|exists:stages,id',
            'is_active' => 'boolean',
        ];
    }

    public function saveAdd(): void
    {
        $this->authorize('create_grade');
        $this->validate();

        Grade::create([
            'name'      => $this->name,
            'stage_id'  => $this->stage_id,
            'is_active' => $this->is_active,
        ]);

        $this->modalAdd = false;
        $this->dispatch('render')->component(GradeData::class);
        $this->success(__('lang.created_successfully', ['attribute' => __('lang.grade')]));
    }

    public function resetData(): void
    {
        $this->reset(['name', 'stage_id']);
        $this->is_active = true;
        $this->resetErrorBag();
        $this->resetValidation();
    }
}

