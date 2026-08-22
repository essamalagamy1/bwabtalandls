<?php

namespace App\Livewire\Dashboard\Admin;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

#[Title('admins')]
#[Lazy]
class AdminData extends Component
{
    use Toast, WithPagination;

    public function placeholder(): View
    {
        return view('livewire.placeholders.page-loading');
    }

    public $all_admin;

    public $all_roles;

    public $search_admin_id;

    public $search_admin_role_id;

    public function mount(): void
    {
        $this->all_admin = User::role('admin')
            ->where('id', '!=', 1)
            ->select('id', 'name', 'username')
            ->get()
            ->toArray();
        $this->all_roles = adminRoles();
        view()->share('breadcrumbs', $this->breadcrumbs());
    }

    public function breadcrumbs(): array
    {
        return [
            [
                'label' => __('lang.admins'),
                'icon' => 'o-user-group',
            ],
        ];
    }

    #[On('render')]
    public function render(): View
    {
        $data['admins'] = User::role('admin')
            ->where('id', '!=', 1)
            ->when($this->search_admin_id, fn (Builder $query) => $query->where('id', $this->search_admin_id))
            ->when($this->search_admin_role_id, fn (Builder $query) => $query->whereHas('roles', fn (Builder $query) => $query->where('id', $this->search_admin_role_id)))
            ->with(['media', 'roles' => function ($query) {
                $query->select('roles.id', 'roles.name');
            }])
            ->latest()
            ->paginate(10);

        return view('livewire.dashboard.admin.admin-data', $data);
    }

    public function toggleStatus($id): void
    {
        $this->authorize('edit_admin');
        $admin = User::findOrFail($id);
        $newStatus = $admin->status === 'active' ? 'inactive' : 'active';
        $admin->update(['status' => $newStatus]);
        $this->success(__('lang.updated_successfully', ['attribute' => __('lang.status')]));
    }

    public function delete($id): void
    {
        $this->authorize('delete_admin');
        User::findOrFail($id)->delete();
        $this->success(__('lang.deleted_successfully', ['attribute' => __('lang.admin')]));
    }
}
