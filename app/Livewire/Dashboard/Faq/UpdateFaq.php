<?php

namespace App\Livewire\Dashboard\Faq;

use App\Models\Faq;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class UpdateFaq extends Component
{
    use Toast;

    public bool $modalUpdate = false;

    public Faq $faq;

    public $question_ar;

    public $question_en;

    public $answer_ar;

    public $answer_en;

    public function mount(): void
    {
        $this->question_ar = $this->faq->getTranslation('question', 'ar');
        $this->question_en = $this->faq->getTranslation('question', 'en');
        $this->answer_ar = $this->faq->getTranslation('answer', 'ar');
        $this->answer_en = $this->faq->getTranslation('answer', 'en');
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

    public function saveUpdate(): void
    {
        $this->authorize('edit_faq');
        $this->validate();
        $this->faq->update([
            'question' => [
                'ar' => $this->question_ar,
                'en' => $this->question_en,
            ],
            'answer' => [
                'ar' => $this->answer_ar,
                'en' => $this->answer_en,
            ],
        ]);
        $this->modalUpdate = false;
        $this->dispatch('render')->component(FaqData::class);
        $this->success(__('lang.updated_successfully', ['attribute' => __('lang.faq')]));
    }

    public function render(): View
    {
        return view('livewire.dashboard.faq.update-faq');
    }

    public function resetError(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
