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
    public $week_id;
    public $semester_id;
    public $publish_date;
    public bool $is_published = false;
    public $training_file;
    public $all_weeks;
    public $all_semesters;

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
            'publish_date'  => 'nullable|date',
            'is_published'  => 'boolean',
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
            'publish_date' => $this->publish_date,
            'is_published' => $this->is_published,
        ]);

        if ($this->training_file) {
            $training->addMedia($this->training_file->getRealPath())
                ->usingFileName($this->training_file->getClientOriginalName())
                ->toMediaCollection('training_file');
        }

        $this->modalAdd = false;
        $this->dispatch('render')->component(TrainingData::class);
        $this->success(__('lang.created_successfully', ['attribute' => __('lang.training')]));
    }

    public function resetData(): void
    {
        $this->reset(['title', 'description', 'url', 'week_id', 'semester_id', 'publish_date', 'training_file']);
        $this->type         = 'video';
        $this->is_published = false;
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
