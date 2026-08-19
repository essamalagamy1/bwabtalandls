<?php

namespace App\Livewire\Dashboard\Training;

use App\Models\Training;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class CreateTraining extends Component
{
    use Toast, WithFileUploads;

    public bool $modalAdd = false;
    public $title;
    public $description;
    public $type = 'video';
    public $url;
    public $stage_id;
    public $grade_id;
    public $semester_id;
    public $week_id;
    
    public bool $is_active = true;
    public $training_file;
    
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
        return view('livewire.dashboard.training.create-training');
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

    public function saveAdd(): void
    {
        $this->authorize('create_training');
        $this->validate();

        $training = Training::create([
            'title'        => $this->title,
            'description'  => $this->description,
            'type'         => $this->type,
            'url'          => $this->url,
            'week_id'      => $this->week_id,
            'semester_id'  => $this->semester_id,
            'is_active'    => $this->is_active,
        ]);

        if ($this->training_file) {
            $training->addMedia($this->training_file->getRealPath())
                ->usingFileName($this->training_file->getClientOriginalName())
                ->toMediaCollection('training_file');
        }

        if ($training->is_active) {
            $gradeId = \App\Models\Semester::find($training->semester_id)?->grade_id;
            if ($gradeId) {
                \App\Jobs\NotifyStudentsOfNewContentJob::dispatch($gradeId, $training->title, 'training');
            }
        }

        $this->modalAdd = false;
        $this->dispatch('render')->component(TrainingData::class);
        $this->success(__('lang.created_successfully', ['attribute' => __('lang.training')]));
    }

    public function resetData(): void
    {
        $this->reset(['title', 'description', 'url', 'stage_id', 'grade_id', 'week_id', 'semester_id', 'training_file']);
        $this->type      = 'video';
        $this->is_active = true;
        $this->all_grades = [];
        $this->all_semesters = [];
        $this->all_weeks = [];
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
