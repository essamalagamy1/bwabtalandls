<?php

namespace App\Livewire\Dashboard\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class CreateUser extends Component
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

    public function render()
    {
        return view('livewire.dashboard.user.create-user');
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
        ];
    }

    public function saveAdd(): void
    {
        $this->authorize('create_user');
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
        $user->assignRole('user');
        $this->modalAdd = false;
        $this->dispatch('render')->component(UserData::class);
        $this->success(__('lang.created_successfully', ['attribute' => __('lang.user')]));
    }

    public function resetData(): void
    {
        $this->reset(['name', 'email', 'password', 'image', 'password_confirmation']);
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
