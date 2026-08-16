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
    public $publish_date;
    public bool $is_published;
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
        $this->publish_date = $this->training->publish_date?->format('Y-m-d');
        $this->is_published = $this->training->is_published;
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
            'publish_date'  => 'nullable|date',
            'is_published'  => 'boolean',
            'training_file' => 'nullable|file|max:51200',
        ];
    }

    public function saveUpdate(): void
    {
        $this->authorize('edit_training');
        $this->validate();

        $this->training->update([
            'title'        => $this->title,
            'description'  => $this->description,
            'type'         => $this->type,
            'url'          => $this->url,
            'week_id'      => $this->week_id,
            'semester_id'  => $this->semester_id,
            'publish_date' => $this->publish_date,
            'is_published' => $this->is_published,
        ]);

        if ($this->training_file) {
            $this->training->addMedia($this->training_file->getRealPath())
                ->usingFileName($this->training_file->getClientOriginalName())
                ->toMediaCollection('training_file');
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
