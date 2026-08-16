<?php

namespace App\Livewire\Student;

use App\Models\Exam;
use App\Models\ExamAttempt;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('exam_result')]
class ExamResult extends Component
{
    public Exam $exam;
    public ExamAttempt $attempt;

    public function mount(Exam $exam): void
    {
        $this->exam = $exam;
        $user = Auth::user();

        $existingAttempt = ExamAttempt::where('exam_id', $exam->id)
            ->where('user_id', $user->id)
            ->first();

        if (!$existingAttempt || $existingAttempt->status === null) {
            $this->redirect(route('student.exams.take', $exam->id), navigate: true);
            return;
        }

        $this->attempt = $existingAttempt;
    }

    public function render(): View
    {
        $this->attempt->load('answers.question');
        return view('livewire.student.exam-result');
    }
}
