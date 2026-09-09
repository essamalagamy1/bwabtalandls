<?php

namespace App\Livewire\Dashboard\Semester;

use App\Models\Grade;
use App\Models\Semester;
use App\Models\Stage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class CreateSemester extends Component
{
    use Toast, WithFileUploads;

    public bool $modalAdd = false;

    public $name;

    public $stage_id;

    public $grade_id;

    public bool $is_active = true;

    public $start_date;

    public $end_date;

    public $academic_year_from;

    public $academic_year_to;

    public $all_stages = [];

    public $all_grades = [];

    public function mount(): void
    {
        $this->all_stages = Stage::where('is_active', true)->get(['id', 'name'])->toArray();
        $this->loadGrades();
    }

    public function updatedStageId($value): void
    {
        $this->grade_id = null;
        $this->loadGrades();
    }

    public function loadGrades(): void
    {
        if (! $this->stage_id) {
            $this->all_grades = [];

            return;
        }

        $this->all_grades = Grade::with('stage:id,name')
            ->where('is_active', true)
            ->where('stage_id', $this->stage_id)
            ->get(['id', 'name', 'stage_id'])
            ->map(function ($grade) {
                return [
                    'id' => $grade->id,
                    'name' => $grade->name,
                    'full_path_name' => $grade->stage?->name ?? '',
                ];
            })->toArray();
    }

    public function render()
    {
        return view('livewire.dashboard.semester.create-semester');
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('semesters', 'name')->where('grade_id', $this->grade_id),
            ],
            'stage_id' => 'nullable|exists:stages,id',
            'grade_id' => 'required|exists:grades,id',
            'is_active' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'academic_year_from' => 'nullable|integer|min:1900|max:2100',
            'academic_year_to' => 'nullable|integer|min:1900|max:2100|gte:academic_year_from',
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
            'name' => $this->name,
            'grade_id' => $this->grade_id,
            'is_active' => $this->is_active,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'academic_year_from' => $this->academic_year_from ?: null,
            'academic_year_to' => $this->academic_year_to ?: null,
        ]);

        $this->modalAdd = false;
        $this->dispatch('render')->component(SemesterData::class);
        $this->success(__('lang.created_successfully', ['attribute' => __('lang.semester')]));
    }

    public function resetData(): void
    {
        $this->reset(['name', 'stage_id', 'grade_id', 'start_date', 'end_date', 'academic_year_from', 'academic_year_to']);
        $this->is_active = true;
        $this->loadGrades();
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
