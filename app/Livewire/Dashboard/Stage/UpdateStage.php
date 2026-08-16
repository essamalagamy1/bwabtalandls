<?php

namespace App\Livewire\Dashboard\Stage;

use App\Models\Stage;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class UpdateStage extends Component
{
    use Toast, WithFileUploads;

    public bool $modalUpdate = false;
    public Stage $stage;
    public $name;
    public bool $is_active;
    public $image;

    public function mount(): void
    {
        $this->name      = $this->stage->name;
        $this->is_active = $this->stage->is_active;
    }

    public function rules(): array
    {
        return [
            'name'      => 'required|string|max:255|unique:stages,name,'.$this->stage->id,
            'is_active' => 'boolean',
            'image'     => 'nullable|image|max:5000|mimes:jpg,jpeg,png,gif,webp,svg',
        ];
    }

    public function saveUpdate(): void
    {
        $this->authorize('edit_stage');
        $this->validate();

        $this->stage->update([
            'name'      => $this->name,
            'is_active' => $this->is_active,
        ]);

        if ($this->image) {
            $this->stage->addMedia($this->image->getRealPath())->toMediaCollection('image');
        }

        $this->modalUpdate = false;
        $this->dispatch('render')->component(StageData::class);
        $this->success(__('lang.updated_successfully', ['attribute' => __('lang.stage')]));
    }

    public function render(): View
    {
        return view('livewire.dashboard.stage.update-stage');
    }

    public function resetError(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
