<?php

namespace App\Livewire\Dashboard\Role;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Title;
use Livewire\Component;
use Mary\Traits\Toast;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

#[Title('edit_course')]
#[Lazy]
class UpdateRole extends Component
{
    use Toast;

    public ?string $name = null;

    public $get_permissions;

    public array $selected_permissions = [];

    public Role $role;

    public function placeholder(): View
    {
        return view('livewire.placeholders.page-loading');
    }

    public function breadcrumbs(): array
    {
        return [
            [
                'label' => __('lang.roles'),
                'icon' => 'o-shield-check',
                'link' => route('roles'),
            ], [
                'label' => __('lang.edit_course'),
            ],

        ];
    }

    public function mount(): void
    {
        $this->name = $this->role->name;
        $this->get_permissions = Permission::get(['id', 'name', 'type'])->groupBy('type')->toArray();
        $this->selected_permissions = $this->role->permissions->pluck('name')->toArray();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'selected_permissions' => 'nullable|array|min:1',
            'selected_permissions.*' => 'nullable|string|exists:permissions,name',
        ];
    }

    public function saveUpdate(): void
    {
        $this->authorize('edit_role');
        $this->validate();
        $this->role->update(['name' => $this->name]);
        $this->role->syncPermissions($this->selected_permissions);
        clearRolesCache();
        $this->success(__('lang.updated_successfully', ['attribute' => __('lang.role')]));
        $this->redirectRoute(name: 'roles', absolute: false, navigate: true);
    }

    public function resetData(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function render(): View
    {
        return view('livewire.dashboard.role.update-role');
    }
}
