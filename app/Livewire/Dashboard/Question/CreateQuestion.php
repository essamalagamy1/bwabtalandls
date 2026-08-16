<?php

namespace App\Livewire\Dashboard\Question;

use App\Models\Question;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class CreateQuestion extends Component
{
    use Toast, WithFileUploads;

    public bool $modalAdd = false;
    public $exam_id;
    public $question_text;
    public $option_a;
    public $option_b;
    public $option_c;
    public $option_d;
    public $correct_answer;
    public $image;
    public $all_exams;

    public function render()
    {
        return view('livewire.dashboard.question.create-question');
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

    public function saveAdd(): void
    {
        $this->authorize('create_question');
        $this->validate();

        $question = Question::create([
            'exam_id'        => $this->exam_id,
            'question_text'  => $this->question_text,
            'option_a'       => $this->option_a,
            'option_b'       => $this->option_b,
            'option_c'       => $this->option_c,
            'option_d'       => $this->option_d,
            'correct_answer' => $this->correct_answer,
        ]);

        if ($this->image) {
            $question->addMedia($this->image->getRealPath())->toMediaCollection('image');
        }

        $this->modalAdd = false;
        $this->dispatch('render')->component(QuestionData::class);
        $this->success(__('lang.created_successfully', ['attribute' => __('lang.question')]));
    }

    public function resetData(): void
    {
        $this->reset(['exam_id', 'question_text', 'option_a', 'option_b', 'option_c', 'option_d', 'correct_answer', 'image']);
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
