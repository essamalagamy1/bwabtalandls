<?php

namespace App\Livewire\Dashboard\Faq;

use App\Models\Faq;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class CreateFaq extends Component
{
    use Toast;

    public bool $modalAdd = false;

    public $question_ar;

    public $question_en;

    public $answer_ar;

    public $answer_en;

    public function render(): View
    {
        return view('livewire.dashboard.faq.create-faq');
    }

    public function rules(): array
    {
        return [
            'question_ar' => 'required|string|max:500',
            'question_en' => 'required|string|max:500',
            'answer_ar' => 'required|string',
            'answer_en' => 'required|string',
        ];
    }

    public function saveAdd(): void
    {
        $this->authorize('create_faq');
        $this->validate();
        Faq::create([
            'question' => [
                'ar' => $this->question_ar,
                'en' => $this->question_en,
            ],
            'answer' => [
                'ar' => $this->answer_ar,
                'en' => $this->answer_en,
            ],
        ]);
        $this->modalAdd = false;
        $this->dispatch('render')->component(FaqData::class);
        $this->success(__('lang.added_successfully', ['attribute' => __('lang.faq')]));
    }

    public function resetData(): void
    {
        $this->reset(['question_ar', 'question_en', 'answer_ar', 'answer_en']);
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
