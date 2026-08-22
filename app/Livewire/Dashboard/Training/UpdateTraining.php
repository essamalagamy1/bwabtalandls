<?php

namespace App\Livewire\Dashboard\Training;

use App\Models\Training;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class UpdateTraining extends Component
{
    use Toast, WithFileUploads;

    public bool $modalUpdate = false;
    public Training $training;
    public $title;
    public $description;
    public $type;
    public $url;
    public $stage_id;
    public $grade_id;
    public $semester_id;
    public $week_id;
    
    public bool $is_active;
    public $training_file;
    
    public $all_stages = [];
    public $all_grades = [];
    public $all_semesters = [];
    public $all_weeks = [];

    public function mount(): void
    {
        $this->title        = $this->training->title;
        $this->description  = $this->training->description;
        $this->type         = $this->training->type;
        $this->url          = $this->training->url;
        $this->week_id      = $this->training->week_id;
        $this->semester_id  = $this->training->semester_id;

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

        $this->is_active    = $this->training->is_active;
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
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'type'          => 'required|in:video,pdf,file,link',
            'url'           => 'nullable|url',
            'week_id'       => 'required|exists:weeks,id',
            'semester_id'   => 'required|exists:semesters,id',
            'is_active'     => 'boolean',
            'training_file' => 'nullable|file|max:51200',
        ];
    }

    public function saveUpdate(): void
    {
        $this->authorize('edit_training');
        $this->validate();

        $wasActive = $this->training->is_active;

        $this->training->update([
            'title'        => $this->title,
            'description'  => $this->description,
            'type'         => $this->type,
            'url'          => $this->url,
            'week_id'      => $this->week_id,
            'semester_id'  => $this->semester_id,
            'is_active'    => $this->is_active,
        ]);

        if ($this->training_file) {
            $this->training->addMedia($this->training_file->getRealPath())
                ->usingFileName($this->training_file->getClientOriginalName())
                ->toMediaCollection('training_file');
        }

        if (!$wasActive && $this->training->is_active) {
            $gradeId = \App\Models\Semester::find($this->training->semester_id)?->grade_id;
            if ($gradeId) {
                $typeLabels = [
                    'video' => 'فيديو',
                    'pdf'   => 'PDF',
                    'file'  => 'ملف',
                    'link'  => 'رابط',
                ];
                \App\Jobs\NotifyStudentsOfNewContentJob::dispatch(
                    $gradeId,
                    $this->training->title,
                    'training',
                    $this->training->description,
                    [
                        'نوع التدريب' => $typeLabels[$this->training->type] ?? $this->training->type,
                    ]
                );
            }
        }

        $this->modalUpdate = false;
        $this->dispatch('render')->component(TrainingData::class);
        $this->success(__('lang.updated_successfully', ['attribute' => __('lang.training')]));
    }

    public function render(): View
    {
        return view('livewire.dashboard.training.update-training');
    }

    public function resetError(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
