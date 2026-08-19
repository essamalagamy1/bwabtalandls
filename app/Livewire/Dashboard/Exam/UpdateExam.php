<?php

namespace App\Livewire\Dashboard\Exam;

use App\Models\Exam;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class UpdateExam extends Component
{
    use Toast, WithFileUploads;

    public bool $modalUpdate = false;
    public Exam $exam;
    public $title;
    public $description;
    public $stage_id;
    public $grade_id;
    public $semester_id;
    public $week_id;
    
    public $duration_minutes;
    public $passing_score;
    public $is_active;

    public $all_stages = [];
    public $all_grades = [];
    public $all_semesters = [];
    public $all_weeks = [];

    public function mount(): void
    {
        $this->title            = $this->exam->title;
        $this->description      = $this->exam->description;
        $this->week_id          = $this->exam->week_id;
        $this->semester_id      = $this->exam->semester_id;
        
        $semester = \App\Models\Semester::with('grade')->find($this->semester_id);
        if ($semester) {
            $this->grade_id = $semester->grade_id;
            $this->stage_id = $semester->grade?->stage_id;
        }

        $this->all_stages = \App\Models\Stage::where('is_active', true)->get();
        if ($this->stage_id) {
            $this->all_grades = \App\Models\Grade::where('stage_id', $this->stage_id)->where('is_active', true)->get();
        }
        if ($this->grade_id) {
            $this->all_semesters = \App\Models\Semester::where('grade_id', $this->grade_id)->where('is_active', true)->get();
        }
        if ($this->semester_id) {
            $this->all_weeks = \App\Models\Week::where('semester_id', $this->semester_id)->where('is_active', true)->get();
        }
        
        $this->duration_minutes = $this->exam->duration_minutes;
        $this->passing_score    = $this->exam->passing_score;
        $this->is_active        = $this->exam->is_active;
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

    public function saveUpdate(): void
    {
        $this->authorize('edit_exam');
        $this->validate();

        $wasActive = $this->exam->is_active;

        $this->exam->update([
            'title'            => $this->title,
            'description'      => $this->description,
            'week_id'          => $this->week_id,
            'semester_id'      => $this->semester_id,
            'duration_minutes' => $this->duration_minutes,
            'passing_score'    => $this->passing_score,
            'is_active'        => (bool) $this->is_active,
        ]);

        if (!$wasActive && $this->exam->is_active) {
            $gradeId = \App\Models\Semester::find($this->exam->semester_id)?->grade_id;
            if ($gradeId) {
                \App\Jobs\NotifyStudentsOfNewContentJob::dispatch($gradeId, $this->exam->title, 'exam');
            }
        }

        $this->modalUpdate = false;
        $this->dispatch('render')->component(ExamData::class);
        $this->success(__('lang.updated_successfully', ['attribute' => __('lang.exam')]));
    }

    public function render(): View
    {
        return view('livewire.dashboard.exam.update-exam');
    }

    public function resetError(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
