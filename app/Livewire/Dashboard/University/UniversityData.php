<?php

namespace App\Livewire\Dashboard\University;

use App\Models\University;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

#[Title('universities')]
#[Lazy]
class UniversityData extends Component
{
    use Toast, WithPagination;

    public function placeholder(): View
    {
        return view('livewire.placeholders.page-loading');
    }

    public string $search = '';

    public function mount(): void
    {
        view()->share('breadcrumbs', $this->breadcrumbs());
    }

    public function breadcrumbs(): array
    {
        return [
            [
                'label' => __('lang.home'),
                'icon' => 'o-home',
                'link' => route('dashboard'),
            ],
            [
                'label' => __('lang.universities'),
            ],
        ];
    }

    public function render(): View
    {
        $universities = University::query()->when($this->search, function ($query) {
            $query->where('name->ar', 'like', "%{$this->search}%")->orWhere('name->en', 'like', "%{$this->search}%");
        })->latest()->paginate(10);

        return view('livewire.dashboard.university.university-data', compact('universities'));
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    #[On('render')]
    public function renderData(): void
    {
        $this->render();
    }

    public function delete($id): void
    {
        $this->authorize('delete_university');

        $university = University::findOrFail($id);

        // Delete image using Media Library
        $university->clearMediaCollection('image');

        $university->delete();
        $this->success(__('lang.deleted_successfully', ['attribute' => __('lang.university')]));
    }
}
