<?php

namespace App\Livewire\Dashboard\Question;

use App\Models\Exam;
use App\Models\Question;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

#[Title('questions')]
#[Lazy]
class QuestionData extends Component
{
    use Toast, WithPagination;

    public function placeholder(): View
    {
        return view('livewire.placeholders.page-loading');
    }

    public $all_exams;

    public $search_text;

    #[Url]
    public $search_exam_id;

    public function mount(): void
    {
        $this->all_exams = Exam::get(['id', 'title as name'])->toArray();
        view()->share('breadcrumbs', $this->breadcrumbs());
    }

    public function breadcrumbs(): array
    {
        return [
            ['label' => __('lang.questions'), 'icon' => 'o-question-mark-circle'],
        ];
    }

    #[On('render')]
    public function render(): View
    {
        $data['questions'] = Question::query()
            ->when($this->search_text, fn (Builder $q) => $q->where('question_text', 'like', "%{$this->search_text}%"))
            ->when($this->search_exam_id, fn (Builder $q) => $q->where('exam_id', $this->search_exam_id))
            ->with('exam')
            ->latest()
            ->paginate(10);

        return view('livewire.dashboard.question.question-data', $data);
    }

    public function delete($id): void
    {
        $this->authorize('delete_question');
        Question::findOrFail($id)->delete();
        $this->success(__('lang.deleted_successfully', ['attribute' => __('lang.question')]));
    }
}
