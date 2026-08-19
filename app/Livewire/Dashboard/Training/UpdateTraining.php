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
    public $week_id;
    public $semester_id;
    public bool $is_active;
    public $training_file;
    public $all_weeks;
    public $all_semesters;

    public function mount(): void
    {
        $this->title        = $this->training->title;
        $this->description  = $this->training->description;
        $this->type         = $this->training->type;
        $this->url          = $this->training->url;
        $this->week_id      = $this->training->week_id;
        $this->semester_id  = $this->training->semester_id;
        $this->is_active    = $this->training->is_active;
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
                \App\Jobs\NotifyStudentsOfNewContentJob::dispatch($gradeId, $this->training->title, 'training');
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
