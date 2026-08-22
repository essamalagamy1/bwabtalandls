<?php

namespace App\Livewire\Dashboard\Section;

use App\Models\Grade;
use App\Models\Section;
use App\Models\Stage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Mary\Traits\Toast;

class CreateSection extends Component
{
    use Toast;

    public bool $modalAdd = false;
    public $stage_id;
    public $grade_id;
    public $name;
    public bool $is_active = true;

    public $all_stages = [];
    public $all_grades = [];

    public function mount(): void
    {
        $this->all_stages = Stage::where('is_active', true)->get();
    }

    public function updatedStageId($stage_id): void
    {
        $this->grade_id = null;
        $this->all_grades = Grade::where('stage_id', $stage_id)->where('is_active', true)->get();
    }

    public function render()
    {
        return view('livewire.dashboard.section.create-section');
    }

    public function rules(): array
    {
        return [
            'stage_id'  => 'required|exists:stages,id',
            'grade_id'  => 'required|exists:grades,id',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('sections', 'name')->where('grade_id', $this->grade_id),
            ],
            'is_active' => 'boolean',
        ];
    }

    public function saveAdd(): void
    {
        $this->authorize('create_section');
        $this->validate();

        Section::create([
            'name'      => $this->name,
            'grade_id'  => $this->grade_id,
            'is_active' => $this->is_active,
        ]);

        $this->modalAdd = false;
        $this->dispatch('render')->component(SectionData::class);
        $this->success(__('lang.created_successfully', ['attribute' => __('lang.section')]));
    }

    public function resetData(): void
    {
        $this->reset(['stage_id', 'grade_id', 'name', 'all_grades']);
        $this->is_active = true;
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
