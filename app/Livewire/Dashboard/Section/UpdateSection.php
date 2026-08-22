<?php

namespace App\Livewire\Dashboard\Section;

use App\Models\Grade;
use App\Models\Section;
use App\Models\Stage;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Mary\Traits\Toast;

class UpdateSection extends Component
{
    use Toast;

    public bool $modalUpdate = false;
    public Section $section;
    public $stage_id;
    public $grade_id;
    public $name;
    public bool $is_active;

    public $all_stages = [];
    public $all_grades = [];

    public function mount(): void
    {
        $this->name      = $this->section->name;
        $this->grade_id  = $this->section->grade_id;
        $this->is_active = $this->section->is_active;

        $grade = Grade::find($this->grade_id);
        if ($grade) {
            $this->stage_id = $grade->stage_id;
        }

        $this->all_stages = Stage::where('is_active', true)->get();
        if ($this->stage_id) {
            $this->all_grades = Grade::where('stage_id', $this->stage_id)->where('is_active', true)->get();
        }
    }

    public function updatedStageId($stage_id): void
    {
        $this->grade_id = null;
        $this->all_grades = Grade::where('stage_id', $stage_id)->where('is_active', true)->get();
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
                Rule::unique('sections', 'name')->where('grade_id', $this->grade_id)->ignore($this->section->id),
            ],
            'is_active' => 'boolean',
        ];
    }

    public function saveUpdate(): void
    {
        $this->authorize('edit_section');
        $this->validate();

        $this->section->update([
            'name'      => $this->name,
            'grade_id'  => $this->grade_id,
            'is_active' => $this->is_active,
        ]);

        $this->modalUpdate = false;
        $this->dispatch('render')->component(SectionData::class);
        $this->success(__('lang.updated_successfully', ['attribute' => __('lang.section')]));
    }

    public function render(): View
    {
        return view('livewire.dashboard.section.update-section');
    }

    public function resetError(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
