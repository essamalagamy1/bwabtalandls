<?php

namespace App\Livewire\Dashboard\Week;

use App\Models\Grade;
use App\Models\Semester;
use App\Models\Stage;
use App\Models\Week;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Mary\Traits\Toast;

class UpdateWeek extends Component
{
    use Toast;

    public bool $modalUpdate = false;

    public Week $week;

    public $title;

    public $order;

    public $semester_id;

    public bool $is_active;

    public $start_date;

    public $end_date;

    public $stage_id;

    public $grade_id;

    public $all_stages = [];

    public $all_grades = [];

    public $all_semesters = [];

    public function mount(): void
    {
        $this->title = $this->week->title;
        $this->order = $this->week->order;
        $this->semester_id = $this->week->semester_id;
        $this->grade_id = $this->week->semester?->grade_id;
        $this->stage_id = $this->week->semester?->grade?->stage_id;
        $this->is_active = $this->week->is_active;
        $this->start_date = $this->week->start_date?->format('Y-m-d');
        $this->end_date = $this->week->end_date?->format('Y-m-d');

        $this->all_stages = Stage::where('is_active', true)->get(['id', 'name'])->toArray();
        $this->loadGrades();
        $this->loadSemesters();
    }

    public function updatedStageId($value): void
    {
        $this->grade_id = null;
        $this->semester_id = null;
        $this->loadGrades();
        $this->loadSemesters();
    }

    public function updatedGradeId($value): void
    {
        $this->semester_id = null;
        $this->loadSemesters();
    }

    public function loadGrades(): void
    {
        if (! $this->stage_id) {
            $this->all_grades = [];
            return;
        }
        $this->all_grades = Grade::where('is_active', true)
            ->where('stage_id', $this->stage_id)
            ->get(['id', 'name'])
            ->toArray();
    }

    public function loadSemesters(): void
    {
        if (! $this->grade_id) {
            $this->all_semesters = [];
            return;
        }
        $this->all_semesters = Semester::where('is_active', true)
            ->where('grade_id', $this->grade_id)
            ->get()
            ->map(function ($semester) {
                return [
                    'id' => $semester->id,
                    'name' => $semester->name_with_academic_year,
                    'full_path_name' => '',
                ];
            })->toArray();
    }

    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('weeks', 'title')->where('semester_id', $this->semester_id)->ignore($this->week->id),
            ],
            'order' => 'required|integer|min:1',
            'semester_id' => 'required|exists:semesters,id',
            'is_active' => 'boolean',
            'start_date' => [
                'nullable',
                'date',
                function (string $attribute, mixed $value, \Closure $fail) {
                    if ($this->semester_id && $value) {
                        $semester = Semester::find($this->semester_id);
                        if ($semester && $semester->start_date && $value < $semester->start_date->format('Y-m-d')) {
                            $fail("تاريخ البداية يجب أن يكون بعد أو يساوي بداية الفصل الدراسي ({$semester->start_date->format('Y-m-d')})");
                        }
                        if ($semester && $semester->end_date && $value > $semester->end_date->format('Y-m-d')) {
                            $fail("تاريخ البداية يجب أن يكون قبل أو يساوي نهاية الفصل الدراسي ({$semester->end_date->format('Y-m-d')})");
                        }
                    }
                },
            ],
            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
                function (string $attribute, mixed $value, \Closure $fail) {
                    if ($this->semester_id && $value) {
                        $semester = Semester::find($this->semester_id);
                        if ($semester && $semester->start_date && $value < $semester->start_date->format('Y-m-d')) {
                            $fail("تاريخ النهاية يجب أن يكون بعد أو يساوي بداية الفصل الدراسي ({$semester->start_date->format('Y-m-d')})");
                        }
                        if ($semester && $semester->end_date && $value > $semester->end_date->format('Y-m-d')) {
                            $fail("تاريخ النهاية يجب أن يكون قبل أو يساوي نهاية الفصل الدراسي ({$semester->end_date->format('Y-m-d')})");
                        }
                    }
                },
            ],
        ];
    }

    public function saveUpdate(): void
    {
        $this->authorize('edit_week');
        $this->validate();

        $this->week->update([
            'title' => $this->title,
            'order' => $this->order,
            'semester_id' => $this->semester_id,
            'is_active' => $this->is_active,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ]);

        $this->modalUpdate = false;
        $this->dispatch('render')->component(WeekData::class);
        $this->success(__('lang.updated_successfully', ['attribute' => __('lang.week')]));
    }

    public function render(): View
    {
        return view('livewire.dashboard.week.update-week');
    }

    public function resetError(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
