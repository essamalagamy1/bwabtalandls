<?php

namespace App\Livewire\Dashboard\Exam;

use App\Jobs\NotifyStudentsOfNewContentJob;
use App\Models\Exam;
use App\Models\Grade;
use App\Models\Semester;
use App\Models\Stage;
use App\Models\Week;
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

    public $media_views_limit = 0;

    public $attachment;

    public $is_active;

    public $all_stages = [];

    public $all_grades = [];

    public $all_semesters = [];

    public $all_weeks = [];

    public function mount(): void
    {
        $this->title = $this->exam->title;
        $this->description = $this->exam->description;
        $this->week_id = $this->exam->week_id;
        $this->semester_id = $this->exam->semester_id;
        $this->media_views_limit = $this->exam->media_views_limit ?? 0;

        $semester = Semester::with('grade')->find($this->semester_id);
        if ($semester) {
            $this->grade_id = $semester->grade_id;
            $this->stage_id = $semester->grade?->stage_id;
        }

        $this->all_stages = Stage::where('is_active', true)->get();
        if ($this->stage_id) {
            $this->all_grades = Grade::where('stage_id', $this->stage_id)->where('is_active', true)->get();
        }
        if ($this->grade_id) {
            $this->all_semesters = Semester::where('grade_id', $this->grade_id)->where('is_active', true)->get();
        }
        if ($this->semester_id) {
            $this->all_weeks = Week::where('semester_id', $this->semester_id)->where('is_active', true)->get();
        }

        $this->duration_minutes = $this->exam->duration_minutes;
        $this->passing_score = $this->exam->passing_score;
        $this->is_active = $this->exam->is_active;
    }

    public function updatedStageId($stage_id)
    {
        $this->grade_id = null;
        $this->semester_id = null;
        $this->week_id = null;
        $this->all_grades = Grade::where('stage_id', $stage_id)->where('is_active', true)->get();
        $this->all_semesters = [];
        $this->all_weeks = [];
    }

    public function updatedGradeId($grade_id)
    {
        $this->semester_id = null;
        $this->week_id = null;
        $this->all_semesters = Semester::where('grade_id', $grade_id)->where('is_active', true)->get();
        $this->all_weeks = [];
    }

    public function updatedSemesterId($semester_id)
    {
        $this->week_id = null;
        $this->all_weeks = Week::where('semester_id', $semester_id)->where('is_active', true)->get();
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'week_id' => 'required|exists:weeks,id',
            'semester_id' => 'required|exists:semesters,id',
            'duration_minutes' => 'required|integer|min:1',
            'passing_score' => 'required|numeric|min:0|max:100',
            'media_views_limit' => 'required|integer|min:0',
            'attachment' => 'nullable|file|mimes:jpeg,jpg,png,webp,gif,mp3,wav,ogg,m4a,aac|max:51200',
            'is_active' => 'boolean',
        ];
    }

    public function deleteAttachment(): void
    {
        $this->authorize('edit_exam');
        $this->exam->clearMediaCollection('attachment');
        $this->exam->refresh();
        $this->success(__('lang.deleted_successfully', ['attribute' => __('lang.attachment_file')]));
    }

    public function saveUpdate(): void
    {
        $this->authorize('edit_exam');
        $this->validate();

        $wasActive = $this->exam->is_active;

        $this->exam->update([
            'title' => $this->title,
            'description' => $this->description,
            'week_id' => $this->week_id,
            'semester_id' => $this->semester_id,
            'duration_minutes' => $this->duration_minutes,
            'passing_score' => $this->passing_score,
            'media_views_limit' => (int) $this->media_views_limit,
            'is_active' => (bool) $this->is_active,
        ]);

        if ($this->attachment) {
            $this->exam->addMedia($this->attachment)->toMediaCollection('attachment');
            $this->attachment = null;
        }

        if (! $wasActive && $this->exam->is_active) {
            $gradeId = Semester::find($this->exam->semester_id)?->grade_id;
            if ($gradeId) {
                NotifyStudentsOfNewContentJob::dispatch(
                    $gradeId,
                    $this->exam->title,
                    'exam',
                    $this->exam->description,
                    [
                        'مدة الاختبار' => $this->exam->duration_minutes.' دقيقة',
                        'درجة النجاح' => $this->exam->passing_score.'%',
                    ]
                );
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
        $this->attachment = null;
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
