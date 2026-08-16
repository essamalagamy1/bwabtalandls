<?php

namespace App\Livewire\Dashboard\Question;

use App\Models\Question;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class UpdateQuestion extends Component
{
    use Toast, WithFileUploads;

    public bool $modalUpdate = false;
    public Question $question;
    public $exam_id;
    public $question_text;
    public $option_a;
    public $option_b;
    public $option_c;
    public $option_d;
    public $correct_answer;
    public $image;
    public $all_exams;

    public function mount(): void
    {
        $this->exam_id        = $this->question->exam_id;
        $this->question_text  = $this->question->question_text;
        $this->option_a       = $this->question->option_a;
        $this->option_b       = $this->question->option_b;
        $this->option_c       = $this->question->option_c;
        $this->option_d       = $this->question->option_d;
        $this->correct_answer = $this->question->correct_answer;
    }

    public function rules(): array
    {
        return [
            'exam_id'        => 'required|exists:exams,id',
            'question_text'  => 'required|string',
            'option_a'       => 'required|string|max:255',
            'option_b'       => 'required|string|max:255',
            'option_c'       => 'required|string|max:255',
            'option_d'       => 'required|string|max:255',
            'correct_answer' => 'required|in:a,b,c,d',
            'image'          => 'nullable|image|max:5000|mimes:jpg,jpeg,png,gif,webp,svg',
        ];
    }

    public function saveUpdate(): void
    {
        $this->authorize('edit_question');
        $this->validate();

        $this->question->update([
            'exam_id'        => $this->exam_id,
            'question_text'  => $this->question_text,
            'option_a'       => $this->option_a,
            'option_b'       => $this->option_b,
            'option_c'       => $this->option_c,
            'option_d'       => $this->option_d,
            'correct_answer' => $this->correct_answer,
        ]);

        if ($this->image) {
            $this->question->addMedia($this->image->getRealPath())->toMediaCollection('image');
        }

        $this->modalUpdate = false;
        $this->dispatch('render')->component(QuestionData::class);
        $this->success(__('lang.updated_successfully', ['attribute' => __('lang.question')]));
    }

    public function render(): View
    {
        return view('livewire.dashboard.question.update-question');
    }

    public function resetError(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
