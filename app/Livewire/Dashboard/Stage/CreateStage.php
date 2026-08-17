<?php

namespace App\Livewire\Dashboard\Stage;

use App\Models\Stage;
use Livewire\Component;
use Mary\Traits\Toast;

class CreateStage extends Component
{
    use Toast;

    public bool $modalAdd = false;
    public $name;
    public bool $is_active = true;

    public function render()
    {
        return view('livewire.dashboard.stage.create-stage');
    }

    public function rules(): array
    {
        return [
            'name'      => 'required|string|max:255|unique:stages,name',
            'is_active' => 'boolean',
        ];
    }

    public function saveAdd(): void
    {
        $this->authorize('create_stage');
        $this->validate();

        Stage::create([
            'name'      => $this->name,
            'is_active' => $this->is_active,
        ]);

        $this->modalAdd = false;
        $this->dispatch('render')->component(StageData::class);
        $this->success(__('lang.created_successfully', ['attribute' => __('lang.stage')]));
    }

    public function resetData(): void
    {
        $this->reset(['name', 'is_active']);
        $this->is_active = true;
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
