<?php

namespace App\Livewire\Dashboard\Stage;

use App\Models\Stage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class CreateStage extends Component
{
    use Toast, WithFileUploads;

    public bool $modalAdd = false;
    public $name;
    public bool $is_active = true;
    public $image;

    public function render()
    {
        return view('livewire.dashboard.stage.create-stage');
    }

    public function rules(): array
    {
        return [
            'name'      => 'required|string|max:255|unique:stages,name',
            'is_active' => 'boolean',
            'image'     => 'nullable|image|max:5000|mimes:jpg,jpeg,png,gif,webp,svg',
        ];
    }

    public function saveAdd(): void
    {
        $this->authorize('create_stage');
        $this->validate();

        $stage = Stage::create([
            'name'      => $this->name,
            'is_active' => $this->is_active,
        ]);

        if ($this->image) {
            $stage->addMedia($this->image->getRealPath())->toMediaCollection('image');
        }

        $this->modalAdd = false;
        $this->dispatch('render')->component(StageData::class);
        $this->success(__('lang.created_successfully', ['attribute' => __('lang.stage')]));
    }

    public function resetData(): void
    {
        $this->reset(['name', 'is_active', 'image']);
        $this->is_active = true;
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
