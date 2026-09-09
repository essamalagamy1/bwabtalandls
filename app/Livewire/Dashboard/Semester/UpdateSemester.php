<?php

namespace App\Livewire\Dashboard\Semester;

use App\Models\Grade;
use App\Models\Semester;
use App\Models\Stage;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class UpdateSemester extends Component
{
    use Toast, WithFileUploads;

    public bool $modalUpdate = false;

    public Semester $semester;

    public $name;

    public $stage_id;

    public $grade_id;

    public bool $is_active;

    public $start_date;

    public $end_date;

    public $academic_year_from;

    public $academic_year_to;

    public $all_stages = [];

    public $all_grades = [];

    public function mount(): void
    {
        $this->name = $this->semester->getRawOriginal('name');
        $this->grade_id = $this->semester->grade_id;
        $this->stage_id = $this->semester->grade?->stage_id;
        $this->is_active = $this->semester->is_active;
        $this->start_date = $this->semester->start_date?->format('Y-m-d');
        $this->end_date = $this->semester->end_date?->format('Y-m-d');
        $this->academic_year_from = $this->semester->academic_year_from;
        $this->academic_year_to = $this->semester->academic_year_to;

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

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('semesters', 'name')->where('grade_id', $this->grade_id)->ignore($this->semester->id),
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
            'academic_year_from' => $this->academic_year_from ?: null,
            'academic_year_to' => $this->academic_year_to ?: null,
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
