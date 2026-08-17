<?php

namespace App\Livewire\Dashboard\Grade;

use App\Models\Grade;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class UpdateGrade extends Component
{
    use Toast;

    public bool $modalUpdate = false;
    public Grade $grade;
    public $name;
    public $stage_id;
    public bool $is_active;
    public $all_stages;

    public function mount(): void
    {
        $this->name      = $this->grade->name;
        $this->stage_id  = $this->grade->stage_id;
        $this->is_active = $this->grade->is_active;
    }

    public function rules(): array
    {
        return [
            'name'      => 'required|string|max:255|unique:grades,name,'.$this->grade->id,
            'stage_id'  => 'required|exists:stages,id',
            'is_active' => 'boolean',
        ];
    }

    public function saveUpdate(): void
    {
        $this->authorize('edit_grade');
        $this->validate();

        $this->grade->update([
            'name'      => $this->name,
            'stage_id'  => $this->stage_id,
            'is_active' => $this->is_active,
        ]);

        $this->modalUpdate = false;
        $this->dispatch('render')->component(GradeData::class);
        $this->success(__('lang.updated_successfully', ['attribute' => __('lang.grade')]));
    }

    public function render(): View
    {
        return view('livewire.dashboard.grade.update-grade');
    }

    public function resetError(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
