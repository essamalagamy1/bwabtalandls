<?php

namespace App\Livewire\Dashboard\Student;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class CreateStudent extends Component
{
    use Toast, WithFileUploads;

    public bool $modalAdd = false;
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $phone;
    public $phone_key;
    public $grade_id;
    public $status = 'active';
    public $image;
    public $all_grades;

    public function render()
    {
        return view('livewire.dashboard.student.create-student');
    }

    public function rules(): array
    {
        return [
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|max:255|unique:users,email',
            'password'  => 'required|string|min:8|confirmed',
            'phone'     => 'required|string|max:20',
            'phone_key' => 'required|string|max:5',
            'grade_id'  => 'required|exists:grades,id',
            'status'    => 'required|in:pending,active,inactive',
            'image'     => 'nullable|image|max:5000|mimes:jpg,jpeg,png,gif,webp,svg',
        ];
    }

    public function saveAdd(): void
    {
        $this->authorize('create_student');
        $this->validate();

        $student = User::create([
            'name'      => $this->name,
            'email'     => $this->email,
            'password'  => Hash::make($this->password),
            'phone'     => $this->phone,
            'phone_key' => $this->phone_key,
            'grade_id'  => $this->grade_id,
            'status'    => $this->status,
        ]);

        $student->assignRole('student');

        if ($this->image) {
            $student->addMedia($this->image->getRealPath())->toMediaCollection('image');
        }

        $this->modalAdd = false;
        $this->dispatch('render')->component(StudentData::class);
        $this->success(__('lang.created_successfully', ['attribute' => __('lang.student')]));
    }

    public function resetData(): void
    {
        $this->reset(['name', 'email', 'password', 'password_confirmation', 'phone', 'phone_key', 'grade_id', 'image']);
        $this->status = 'active';
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
