<?php

namespace App\Livewire\Dashboard\Student;

use App\Models\Grade;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

#[Title('students')]
#[Lazy]
class StudentData extends Component
{
    use Toast, WithPagination;

    public function placeholder(): View
    {
        return view('livewire.placeholders.page-loading');
    }

    public $all_grades;
    public $search_name;
    public $search_grade_id;
    public $search_status = '';

    public function mount(): void
    {
        $this->all_grades = Grade::get(['id', 'name'])->toArray();
        view()->share('breadcrumbs', $this->breadcrumbs());
    }

    public function breadcrumbs(): array
    {
        return [
            ['label' => __('lang.students'), 'icon' => 'o-users'],
        ];
    }

    #[On('render')]
    public function render(): View
    {
        $data['students'] = User::role('student')
            ->when($this->search_name, fn(Builder $q) => $q->where('name', 'like', "%{$this->search_name}%")->orWhere('email', 'like', "%{$this->search_name}%"))
            ->when($this->search_grade_id, fn(Builder $q) => $q->where('grade_id', $this->search_grade_id))
            ->when($this->search_status !== '', fn(Builder $q) => $q->where('status', $this->search_status))
            ->with('grade.stage')
            ->latest()
            ->paginate(10);

        return view('livewire.dashboard.student.student-data', $data);
    }

    public function delete($id): void
    {
        $this->authorize('delete_student');
        User::findOrFail($id)->delete();
        $this->success(__('lang.deleted_successfully', ['attribute' => __('lang.student')]));
    }
}
