<?php

namespace App\Livewire\Dashboard\Instructor;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class UpdateInstructor extends Component
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

    public $university_id;

    public $universities;

    public function mount(): void
    {
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->phone = $this->user->phone;
        $this->phone_key = $this->user->phone_key;
        $this->university_id = $this->user->university_id;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email:filter|max:255|unique:users,email,'.$this->user->id,
            'phone' => 'nullable|string|max:20|unique:users,phone,'.$this->user->id,
            'phone_key' => 'nullable|string|max:10',
            'university_id' => 'nullable|exists:universities,id',
            'password' => 'nullable|string|min:8|confirmed',
            'image' => 'nullable|image|max:5000|mimes:jpg,jpeg,png,gif,webp,svg',
        ];
    }

    public function saveUpdate(): void
    {
        $this->authorize('edit_instructor');
        $this->validate();
        $this->user->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'phone_key' => $this->phone_key,
            'university_id' => $this->university_id,
        ]);
        if ($this->image) {
            $this->user->addMedia($this->image->getRealPath())->toMediaCollection('image');
        }
        if ($this->password) {
            $this->user->update(['password' => $this->password]);
        }
        $this->modalUpdate = false;
        $this->dispatch('render')->component(InstructorData::class);
        $this->success(__('lang.updated_successfully', ['attribute' => __('lang.instructor')]));
    }

    public function render(): View
    {
        return view('livewire.dashboard.instructor.update-instructor');
    }

    public function resetError(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
