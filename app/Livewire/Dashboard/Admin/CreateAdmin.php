<?php

namespace App\Livewire\Dashboard\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;
use Spatie\Permission\Models\Role;

class CreateAdmin extends Component
{
    use Toast, WithFileUploads;

    public bool $modalAdd = false;

    public $name;

    public $email;

    public $password;

    public $image;

    public $phone;

    public $phone_key;

    public $password_confirmation;

    public $roles = [];

    public $all_roles;

    public function render()
    {
        return view('livewire.dashboard.admin.create-admin');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email:filter|max:255|unique:users,email',
            'password' => 'nullable|string|min:8|confirmed',
            'image' => 'nullable|image|max:5000|mimes:jpg,jpeg,png,gif,webp,svg',
            'phone' => 'required|string|max:20|unique:users,phone',
            'phone_key' => 'required|string|max:5',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,id',
        ];
    }

    public function saveAdd(): void
    {
        $this->authorize('create_admin');
        $this->validate();
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'phone' => $this->phone,
            'phone_key' => $this->phone_key,
        ]);
        if ($this->image) {
            $user->addMedia($this->image->getRealPath())->toMediaCollection('image');
        }
        $user->assignRole('admin');

        // Assign selected roles
        $roleNames = Role::whereIn('id', $this->roles)->pluck('name')->toArray();
        $user->syncRoles(array_merge(['admin'], $roleNames));

        $this->modalAdd = false;
        $this->dispatch('render')->component(AdminData::class);
        $this->success(__('lang.created_successfully', ['attribute' => __('lang.admin')]));
    }

    public function resetData(): void
    {
        $this->reset(['name', 'email', 'password', 'image', 'password_confirmation', 'phone', 'phone_key', 'roles']);
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
