<?php

namespace App\Livewire\Dashboard\Semester;

use App\Models\Semester;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class CreateSemester extends Component
{
    use Toast, WithFileUploads;

    public bool $modalAdd = false;
    public $name;
    public $grade_id;
    public bool $is_active = true;
    public $start_date;
    public $end_date;
    public $all_grades;

    public function render()
    {
        return view('livewire.dashboard.semester.create-semester');
    }

    public function rules(): array
    {
        return [
            'name'       => [
                'required',
                'string',
                'max:255',
                \Illuminate\Validation\Rule::unique('semesters', 'name')->where('grade_id', $this->grade_id)
            ],
            'grade_id'   => 'required|exists:grades,id',
            'is_active'  => 'boolean',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ];
    }

    public function saveAdd(): void
    {
        $this->authorize('create_semester');
        $this->validate();

        if ($this->is_active) {
            $exists = Semester::where('grade_id', $this->grade_id)
                ->where('is_active', true)
                ->exists();
            if ($exists) {
                $this->addError('is_active', 'لا يمكن تفعيل هذا الفصل لوجود فصل آخر مفعل لنفس الصف.');
                return;
            }
        }

        $semester = Semester::create([
            'name'       => $this->name,
            'grade_id'   => $this->grade_id,
            'is_active'  => $this->is_active,
            'start_date' => $this->start_date,
            'end_date'   => $this->end_date,
        ]);
     

        $this->modalAdd = false;
        $this->dispatch('render')->component(SemesterData::class);
        $this->success(__('lang.created_successfully', ['attribute' => __('lang.semester')]));
    }

    public function resetData(): void
    {
        $this->reset(['name', 'grade_id', 'start_date', 'end_date']);
        $this->is_active = true;
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
