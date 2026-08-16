<?php

namespace App\Livewire\Dashboard\ExamAttempt;

use App\Models\Exam;
use App\Models\ExamAttempt;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('exam_attempts')]
#[Lazy]
class ExamAttemptData extends Component
{
    use WithPagination;

    public function placeholder(): View
    {
        return view('livewire.placeholders.page-loading');
    }

    public $all_exams;
    public $search_student_name;
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
            ['label' => __('lang.exam_attempts_mng'), 'icon' => 'o-users'],
        ];
    }

    #[On('render')]
    public function render(): View
    {
        $data['attempts'] = ExamAttempt::query()
            ->when($this->search_student_name, function (Builder $q) {
                $q->whereHas('user', function (Builder $uq) {
                    $uq->where('name', 'like', "%{$this->search_student_name}%");
                });
            })
            ->when($this->search_exam_id, fn(Builder $q) => $q->where('exam_id', $this->search_exam_id))
            ->with(['exam', 'user'])
            ->latest()
            ->paginate(10);

        return view('livewire.dashboard.exam-attempt.exam-attempt-data', $data);
    }
}
