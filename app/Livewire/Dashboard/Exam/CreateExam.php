<?php

namespace App\Livewire\Dashboard\Exam;

use App\Models\Exam;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class CreateExam extends Component
{
    use Toast, WithFileUploads;

    public bool $modalAdd = false;
    public $title;
    public $description;
    public $stage_id;
    public $grade_id;
    public $semester_id;
    public $week_id;

    public $duration_minutes;
    public $passing_score;
    public $is_active = true;
    
    public $all_stages = [];
    public $all_grades = [];
    public $all_semesters = [];
    public $all_weeks = [];

    public function mount()
    {
        $this->all_stages = \App\Models\Stage::where('is_active', true)->get();
    }

    public function updatedStageId($stage_id)
    {
        $this->grade_id = null;
        $this->semester_id = null;
        $this->week_id = null;
        $this->all_grades = \App\Models\Grade::where('stage_id', $stage_id)->where('is_active', true)->get();
        $this->all_semesters = [];
        $this->all_weeks = [];
    }

    public function updatedGradeId($grade_id)
    {
        $this->semester_id = null;
        $this->week_id = null;
        $this->all_semesters = \App\Models\Semester::where('grade_id', $grade_id)->where('is_active', true)->get();
        $this->all_weeks = [];
    }

    public function updatedSemesterId($semester_id)
    {
        $this->week_id = null;
        $this->all_weeks = \App\Models\Week::where('semester_id', $semester_id)->where('is_active', true)->get();
    }

    public function render()
    {
        return view('livewire.dashboard.exam.create-exam');
    }

    public function rules(): array
    {
        return [
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'week_id'          => 'required|exists:weeks,id',
            'semester_id'      => 'required|exists:semesters,id',
            'duration_minutes' => 'required|integer|min:1',
            'passing_score'    => 'required|numeric|min:0|max:100',
            'is_active'        => 'boolean',
        ];
    }

    public function saveAdd(): void
    {
        $this->authorize('create_exam');
        $this->validate();

        $exam = Exam::create([
            'title'            => $this->title,
            'description'      => $this->description,
            'week_id'          => $this->week_id,
            'semester_id'      => $this->semester_id,
            'duration_minutes' => $this->duration_minutes,
            'passing_score'    => $this->passing_score,
            'is_active'        => (bool) $this->is_active,
        ]);

        if ($exam->is_active) {
            $gradeId = \App\Models\Semester::find($exam->semester_id)?->grade_id;
            if ($gradeId) {
                \App\Jobs\NotifyStudentsOfNewContentJob::dispatch(
                    $gradeId,
                    $exam->title,
                    'exam',
                    $exam->description,
                    [
                        'مدة الاختبار' => $exam->duration_minutes . ' دقيقة',
                        'درجة النجاح' => $exam->passing_score . '%',
                    ]
                );
            }
        }

        $this->modalAdd = false;
        $this->dispatch('render')->component(ExamData::class);
        $this->success(__('lang.created_successfully', ['attribute' => __('lang.exam')]));
    }

    public function resetData(): void
    {
        $this->reset(['title', 'description', 'stage_id', 'grade_id', 'week_id', 'semester_id', 'duration_minutes', 'passing_score', 'is_active']);
        $this->all_grades = [];
        $this->all_semesters = [];
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
