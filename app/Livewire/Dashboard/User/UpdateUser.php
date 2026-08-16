<?php

namespace App\Livewire\Dashboard\User;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class UpdateUser extends Component
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

    public function mount(): void
    {

        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->phone = $this->user->phone;
        $this->phone_key = $this->user->phone_key;
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
        ];
    }

    public function saveUpdate(): void
    {
        $this->authorize('edit_user');
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
        $this->modalUpdate = false;
        $this->dispatch('render')->component(UserData::class);
        $this->success(__('lang.updated_successfully', ['attribute' => __('lang.user')]));
    }

    public function render(): View
    {
        return view('livewire.dashboard.user.update-user');
    }

    public function resetError(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
