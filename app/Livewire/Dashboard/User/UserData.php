<?php

namespace App\Livewire\Dashboard\User;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

#[Title('users')]
#[Lazy]
class UserData extends Component
{
    use Toast, WithPagination;

    public function placeholder(): View
    {
        return view('livewire.placeholders.page-loading');
    }

    public $all_user;

    public $search_user_id;

    public function mount(): void
    {
        $this->all_user = User::role('user')->get(['id', 'name', 'username'])->toArray();
        view()->share('breadcrumbs', $this->breadcrumbs());
    }

    public function breadcrumbs(): array
    {
        return [
            [
                'label' => __('lang.users'),
                'icon' => 'o-users',
            ],
        ];
    }

    #[On('render')]
    public function render(): View
    {
        $data['users'] = User::role('user')
            ->when($this->search_user_id, fn (Builder $query) => $query->where('id', $this->search_user_id))
            ->with('media')
            ->latest()
            ->paginate(10);

        return view('livewire.dashboard.user.user-data', $data);
    }

    public function delete($id): void
    {
        $this->authorize('delete_user');
        User::findOrFail($id)->delete();
        $this->success(__('lang.deleted_successfully', ['attribute' => __('lang.user')]));
    }
}
