<?php

namespace App\Livewire\Student;

use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\StudentAnswer;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;
use Mary\Traits\Toast;

#[Title('enter_exam')]
class TakeExam extends Component
{
    use Toast;

    public Exam $exam;

    public ExamAttempt $attempt;

    public array $answers = [];

    public int $timeLeft = 0;

    public bool $isMediaOpen = false;

    public function mount(Exam $exam): void
    {
        $this->exam = $exam->load('questions');
        $user = Auth::user();

        $existingAttempt = ExamAttempt::where('exam_id', $exam->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existingAttempt) {
            if ($existingAttempt->status !== null) {
                $this->redirect(route('student.exams.result', $exam->id), navigate: true);

                return;
            }
            $this->attempt = $existingAttempt;
        } else {
            $this->attempt = ExamAttempt::create([
                'exam_id' => $exam->id,
                'user_id' => $user->id,
                'status' => null,
                'started_at' => now(),
            ]);
        }

        if ($this->exam->isUnlimitedMediaViews()) {
            $this->isMediaOpen = true;
        }

        foreach ($this->exam->questions as $question) {
            $this->answers[$question->id] = null;
        }

        if ($this->exam->duration_minutes > 0) {
            $elapsedSeconds = (int) now()->diffInSeconds($this->attempt->started_at);
            $this->timeLeft = (int) max(0, ($this->exam->duration_minutes * 60) - $elapsedSeconds);
            if ($this->timeLeft <= 0) {
                $this->submitExam();

                return;
            }
        } else {
            $this->timeLeft = -1;
        }
    }

    public function openMedia(): void
    {
        if ($this->exam->isUnlimitedMediaViews()) {
            $this->isMediaOpen = true;

            return;
        }

        if ($this->attempt->canViewMedia()) {
            $this->attempt->increment('media_views_count');
            $this->attempt->refresh();
            $this->isMediaOpen = true;
        } else {
            $this->isMediaOpen = false;
            $this->error(__('lang.views_exhausted'));
        }
    }

    public function closeMedia(): void
    {
        $this->isMediaOpen = false;
    }

    public function submitExam(): void
    {
        $correctAnswersCount = 0;
        $totalQuestions = $this->exam->questions->count();

        foreach ($this->exam->questions as $question) {
            $selectedOption = $this->answers[$question->id] ?? null;
            $isCorrect = $selectedOption === $question->correct_answer;

            if ($isCorrect) {
                $correctAnswersCount++;
            }

            StudentAnswer::updateOrCreate(
                [
                    'exam_attempt_id' => $this->attempt->id,
                    'question_id' => $question->id,
                ],
                [
                    'selected_option' => $selectedOption,
                    'is_correct' => $isCorrect,
                ]
            );
        }

        $totalScore = $totalQuestions > 0 ? ($correctAnswersCount / $totalQuestions) * 100 : 0;
        $status = $totalScore >= $this->exam->passing_score ? 'passed' : 'failed';

        $this->attempt->update([
            'total_score' => $totalScore,
            'status' => $status,
            'completed_at' => now(),
        ]);

        $this->success(__('lang.exam_submitted_successfully'));
        $this->redirect(route('student.exams.result', $this->exam->id), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.student.take-exam');
    }
}
