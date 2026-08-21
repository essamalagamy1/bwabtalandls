<?php

namespace App\Livewire\Dashboard\Semester;

use App\Models\Semester;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class UpdateSemester extends Component
{
    use Toast, WithFileUploads;

    public bool $modalUpdate = false;

    public Semester $semester;

    public $name;

    public $grade_id;

    public bool $is_active;

    public $start_date;

    public $end_date;

    public $all_grades;

    public function mount(): void
    {
        $this->name = $this->semester->name;
        $this->grade_id = $this->semester->grade_id;
        $this->is_active = $this->semester->is_active;
        $this->start_date = $this->semester->start_date?->format('Y-m-d');
        $this->end_date = $this->semester->end_date?->format('Y-m-d');
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                \Illuminate\Validation\Rule::unique('semesters', 'name')->where('grade_id', $this->grade_id)->ignore($this->semester->id)
            ],
            'grade_id' => 'required|exists:grades,id',
            'is_active' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ];
    }

    public function saveUpdate(): void
    {
        $this->authorize('edit_semester');
        $this->validate();

        if ($this->is_active) {
            $exists = Semester::where('grade_id', $this->grade_id)
                ->where('is_active', true)
                ->where('id', '!=', $this->semester->id)
                ->exists();
            if ($exists) {
                $this->addError('is_active', 'لا يمكن تفعيل هذا الفصل لوجود فصل آخر مفعل لنفس الصف.');
                return;
            }
        }

        $this->semester->update([
            'name' => $this->name,
            'grade_id' => $this->grade_id,
            'is_active' => $this->is_active,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ]);

        $this->modalUpdate = false;
        $this->dispatch('render')->component(SemesterData::class);
        $this->success(__('lang.updated_successfully', ['attribute' => __('lang.semester')]));
    }

    public function render(): View
    {
        return view('livewire.dashboard.semester.update-semester');
    }

    public function resetError(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
