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
    public $week_id;
    public $semester_id;
    public $duration_minutes;
    public $passing_score;
    public $assignment_date;
    public $image;
    public $all_weeks;

    public function mount(): void
    {
        $this->title            = $this->exam->title;
        $this->description      = $this->exam->description;
        $this->week_id          = $this->exam->week_id;
        $this->semester_id      = $this->exam->semester_id;
        $this->duration_minutes = $this->exam->duration_minutes;
        $this->passing_score    = $this->exam->passing_score;
        $this->assignment_date  = $this->exam->assignment_date?->format('Y-m-d\TH:i');
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
            'assignment_date'  => 'nullable|date',
            'image'            => 'nullable|image|max:5000|mimes:jpg,jpeg,png,gif,webp,svg',
        ];
    }

    public function saveUpdate(): void
    {
        $this->authorize('edit_exam');
        $this->validate();

        $this->exam->update([
            'title'            => $this->title,
            'description'      => $this->description,
            'week_id'          => $this->week_id,
            'semester_id'      => $this->semester_id,
            'duration_minutes' => $this->duration_minutes,
            'passing_score'    => $this->passing_score,
            'assignment_date'  => $this->assignment_date,
        ]);

        if ($this->image) {
            $this->exam->addMedia($this->image->getRealPath())->toMediaCollection('image');
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
