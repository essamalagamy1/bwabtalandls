<?php

namespace App\Livewire\Dashboard\Exam;

use App\Models\Exam;
use App\Models\Week;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

#[Title('exams')]
#[Lazy]
class ExamData extends Component
{
    use Toast, WithPagination;

    public function placeholder(): View
    {
        return view('livewire.placeholders.page-loading');
    }

    public $all_weeks;
    public $search_title;
    #[Url]
    public $search_week_id;

    public function mount(): void
    {
        $this->all_weeks = Week::get(['id', 'title as name'])->toArray();
        view()->share('breadcrumbs', $this->breadcrumbs());
    }

    public function breadcrumbs(): array
    {
        return [
            ['label' => __('lang.exams'), 'icon' => 'o-document-text'],
        ];
    }

    #[On('render')]
    public function render(): View
    {
        $data['exams'] = Exam::query()
            ->when($this->search_title, fn(Builder $q) => $q->where('title', 'like', "%{$this->search_title}%"))
            ->when($this->search_week_id, fn(Builder $q) => $q->where('week_id', $this->search_week_id))
            ->with(['week.semester'])
            ->withCount('questions')
            ->latest()
            ->paginate(10);

        return view('livewire.dashboard.exam.exam-data', $data);
    }

    public function delete($id): void
    {
        $this->authorize('delete_exam');
        Exam::findOrFail($id)->delete();
        $this->success(__('lang.deleted_successfully', ['attribute' => __('lang.exam')]));
    }
}
