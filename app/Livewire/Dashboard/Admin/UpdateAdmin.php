<?php

namespace App\Livewire\Dashboard\Admin;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;
use Spatie\Permission\Models\Role;

class UpdateAdmin extends Component
{
    use Toast, WithFileUploads;

    public bool $modalUpdate = false;

    public User $user;

    public $name;

    public $email;

    public $password;

    public $image;

    public $password_confirmation;

    public $phone;

    public $phone_key;

    public $roles = [];

    public $all_roles;

    public function mount(): void
    {
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->phone = $this->user->phone;
        $this->phone_key = $this->user->phone_key;
        // Use the already loaded roles relationship to avoid N+1 queries
        $this->roles = $this->user->roles->where('name', '!=', 'admin')->pluck('id')->toArray();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email:filter|max:255|unique:users,email,'.$this->user->id,
            'phone' => 'nullable|string|max:20|unique:users,phone,'.$this->user->id,
            'phone_key' => 'nullable|string|max:10',
            'password' => 'nullable|string|min:8|confirmed',
            'image' => 'nullable|image|max:5000|mimes:jpg,jpeg,png,gif,webp,svg',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,id',
        ];
    }

    public function saveUpdate(): void
    {
        $this->authorize('edit_admin');
        $this->validate();
        $this->user->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'phone_key' => $this->phone_key,
        ]);
        if ($this->image) {
            $this->user->addMedia($this->image->getRealPath())->toMediaCollection('image');
        }
        if ($this->password) {
            $this->user->update(['password' => Hash::make($this->password)]);
        }

        // Sync roles
        $roleNames = Role::whereIn('id', $this->roles)->pluck('name')->toArray();
        $this->user->syncRoles(array_merge(['admin'], $roleNames));

        $this->modalUpdate = false;
        $this->dispatch('render')->component(AdminData::class);
        $this->success(__('lang.updated_successfully', ['attribute' => __('lang.admin')]));
    }

    public function render(): View
    {
        return view('livewire.dashboard.admin.update-admin');
    }

    public function resetError(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
