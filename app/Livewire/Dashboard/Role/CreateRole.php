<?php

namespace App\Livewire\Dashboard\Role;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Title;
use Livewire\Component;
use Mary\Traits\Toast;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

#[Title('create_role')]
#[Lazy]
class CreateRole extends Component
{
    use Toast;

    public ?string $name = null;

    public $get_permissions;

    public array $selected_permissions = [];

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
                'label' => __('lang.create_role'),
            ],

        ];
    }

    public function mount(): void
    {
        $this->get_permissions = Permission::get(['id', 'name', 'type'])->groupBy('type')->toArray();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'selected_permissions' => 'nullable|array|min:1',
            'selected_permissions.*' => 'nullable|string|exists:permissions,name',
        ];
    }

    public function saveCreate(): void
    {
        $this->authorize('create_role');
        $this->validate();
        $role = Role::create(['name' => $this->name]);
        $role->givePermissionTo($this->selected_permissions);
        clearRolesCache();
        $this->success(__('lang.added_successfully', ['attribute' => __('lang.role')]));
        $this->redirectRoute(name: 'roles', absolute: false, navigate: true);
    }

    public function render(): View
    {
        return view('livewire.dashboard.role.create-role');
    }
}
