<?php

namespace App\Livewire\Dashboard\Profile;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

#[Title('profile')]
class Profile extends Component
{
    use Toast, WithFileUploads;

    public $name;

    public $image;

    public $old_password;

    public $password;

    public $password_confirmation;

    public function mount(): void
    {
        $this->name = auth()->user()->name;
        view()->share('breadcrumbs', $this->breadcrumbs());
    }

    public function breadcrumbs(): array
    {
        return [
            [
                'label' => __('lang.profile'),
                'icon' => 'fas.user-cog',
            ],
        ];
    }

    public function render(): View
    {
        return view('livewire.dashboard.profile.profile');
    }

    public function updateProfile(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:5000',
        ]);
        auth()->user()->update(['name' => $this->name]);
        if ($this->image) {
            auth()->user()->addMedia($this->image->getRealPath())->toMediaCollection('image');
        }
        $this->success(__('lang.profile_updated_successfully'));
    }

    public function updatePassword(): void
    {
        $this->validate([
            'old_password' => 'required|string|min:8',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
        ]);
        if (! Hash::check($this->old_password, auth()->user()->password)) {
            $this->addError('old_password', __('lang.old_password_is_incorrect'));

            return;
        }
        auth()->user()->update(['password' => Hash::make($this->password)]);
        $this->reset(['old_password', 'password', 'password_confirmation']);
        $this->success(__('lang.password_updated_successfully'));
    }
}
