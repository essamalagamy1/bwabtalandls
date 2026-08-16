<?php

namespace App\Livewire\Dashboard\Instructor;

use App\Models\University;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

#[Title('instructors')]
#[Lazy]
class InstructorData extends Component
{
    use Toast, WithPagination;

    public function placeholder(): View
    {
        return view('livewire.placeholders.page-loading');
    }

    public $all_instructor;

    public $universities;

    public $search_instructor_id;

    public $search_university_id;

    public function mount(): void
    {
        $this->all_instructor = User::role('instructor')->get(['id', 'name', 'username'])->toArray();
        $this->universities = University::active()->get()->map(function ($university) {
            return [
                'id' => $university->id,
                'name' => $university->name,
            ];
        })->toArray();
        view()->share('breadcrumbs', $this->breadcrumbs());
    }

    public function breadcrumbs(): array
    {
        return [
            [
                'label' => __('lang.instructors'),
                'icon' => 'o-academic-cap',
            ],
        ];
    }

    #[On('render')]
    public function render(): View
    {
        $data['instructors'] = User::role('instructor')
            ->when($this->search_instructor_id, fn (Builder $query) => $query->where('id', $this->search_instructor_id))
            ->when($this->search_university_id, fn (Builder $query) => $query->where('university_id', $this->search_university_id))
            ->with(['media', 'roles', 'university'])
            ->latest()
            ->paginate(10);

        return view('livewire.dashboard.instructor.instructor-data', $data);
    }

    public function delete($id): void
    {
        $this->authorize('delete_instructor');
        User::findOrFail($id)->delete();
        $this->success(__('lang.deleted_successfully', ['attribute' => __('lang.instructor')]));
    }
}
