<?php

namespace App\Livewire\Dashboard\Faq;

use App\Models\Faq;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

#[Title('faqs')]
#[Lazy]
class FaqData extends Component
{
    use Toast, WithPagination;

    public function placeholder(): View
    {
        return view('livewire.placeholders.page-loading');
    }

    public $all_faq;

    public $search_faq_id;

    public function mount(): void
    {
        $this->all_faq = Faq::get(['id', 'question'])->map(function ($faq) {
            return [
                'id' => $faq->id,
                'question' => $faq->question,
            ];
        })->toArray();
        view()->share('breadcrumbs', $this->breadcrumbs());
    }

    public function breadcrumbs(): array
    {
        return [
            [
                'label' => __('lang.faqs'),
                'icon' => 'o-question-mark-circle',
            ],
        ];
    }

    #[On('render')]
    public function render(): View
    {
        $data['faqs'] = Faq::query()
            ->when($this->search_faq_id, fn (Builder $query) => $query->where('id', $this->search_faq_id))
            ->latest()
            ->paginate(20);

        return view('livewire.dashboard.faq.faq-data', $data);
    }

    public function delete($id): void
    {
        $this->authorize('delete_faq');
        $faq = Faq::findOrFail($id);
        $faq->delete();
        $this->success(__('lang.deleted_successfully', ['attribute' => __('lang.faq')]));
    }
}
