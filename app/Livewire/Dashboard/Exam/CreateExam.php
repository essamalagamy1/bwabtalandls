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
    public $week_id;
    public $semester_id;
    public $duration_minutes;
    public $passing_score;
    public $is_active = true;
    public $all_weeks;

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
                \App\Jobs\NotifyStudentsOfNewContentJob::dispatch($gradeId, $exam->title, 'exam');
            }
        }

        $this->modalAdd = false;
        $this->dispatch('render')->component(ExamData::class);
        $this->success(__('lang.created_successfully', ['attribute' => __('lang.exam')]));
    }

    public function resetData(): void
    {
        $this->reset(['title', 'description', 'week_id', 'semester_id', 'duration_minutes', 'passing_score', 'is_active']);
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
