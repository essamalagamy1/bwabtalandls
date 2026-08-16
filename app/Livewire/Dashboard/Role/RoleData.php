<?php

namespace App\Livewire\Dashboard\Role;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use Spatie\Permission\Models\Role;

#[Title('roles')]
#[Lazy]
class RoleData extends Component
{
    use Toast, WithPagination;

    public $search_role_id;

    public $all_roles;

    public function placeholder(): View
    {
        return view('livewire.placeholders.page-loading');
    }

    public function mount(): void
    {
        $this->all_roles = Role::whereNot('name', 'master admin')->where('is_main', false)->get(['id', 'name'])->toArray();
        view()->share('breadcrumbs', $this->breadcrumbs());
    }

    public function breadcrumbs(): array
    {
        return [
            [
                'label' => __('lang.roles'),
                'icon' => 'o-shield-check',
            ],
        ];
    }

    #[On('render')]
    public function render(): View
    {
        $data['roles'] = Role::when($this->search_role_id, fn (Builder $query) => $query->where('id', $this->search_role_id))
            ->whereNot('name', 'master admin')
            ->where('is_main', false)
            ->with('permissions')
            ->withCount(['users', 'permissions'])
            ->latest()->paginate(30);

        return view('livewire.dashboard.role.role-data', $data);
    }

    public function delete($id): void
    {
        $this->authorize('delete_role');
        Role::findOrFail($id)->delete();
        clearRolesCache();
        $this->success(__('lang.deleted_successfully', ['attribute' => __('lang.role')]));
    }
}
