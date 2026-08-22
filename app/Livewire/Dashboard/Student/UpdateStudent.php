<?php

namespace App\Livewire\Dashboard\Student;

use App\Models\Grade;
use App\Models\Section;
use App\Models\Stage;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class UpdateStudent extends Component
{
    use Toast, WithFileUploads;

    public bool $modalUpdate = false;
    public User $student;
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $phone;
    public $phone_key;
    public $stage_id;
    public $grade_id;
    public $section_id;
    public $status;
    public $image;

    public $all_stages = [];
    public $all_grades = [];
    public $all_sections = [];

    public function mount(): void
    {
        $this->name       = $this->student->name;
        $this->email      = $this->student->email;
        $this->phone      = $this->student->phone;
        $this->phone_key  = $this->student->phone_key;
        $this->grade_id   = $this->student->grade_id;
        $this->section_id = $this->student->section_id;
        $this->status     = $this->student->status;

        $grade = Grade::find($this->grade_id);
        if ($grade) {
            $this->stage_id = $grade->stage_id;
        }

        $this->all_stages = Stage::where('is_active', true)->get();
        if ($this->stage_id) {
            $this->all_grades = Grade::where('stage_id', $this->stage_id)->where('is_active', true)->get();
        }
        if ($this->grade_id) {
            $this->all_sections = Section::where('grade_id', $this->grade_id)->where('is_active', true)->get();
        }
    }

    public function updatedStageId($stage_id): void
    {
        $this->grade_id = null;
        $this->section_id = null;
        $this->all_grades = Grade::where('stage_id', $stage_id)->where('is_active', true)->get();
        $this->all_sections = [];
    }

    public function updatedGradeId($grade_id): void
    {
        $this->section_id = null;
        $this->all_sections = Section::where('grade_id', $grade_id)->where('is_active', true)->get();
    }

    public function rules(): array
    {
        return [
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|max:255|unique:users,email,'.$this->student->id,
            'password'   => 'nullable|string|min:8|confirmed',
            'phone'      => 'required|string|max:20',
            'phone_key'  => 'required|string|max:5',
            'stage_id'   => 'required|exists:stages,id',
            'grade_id'   => 'required|exists:grades,id',
            'section_id' => 'nullable|exists:sections,id',
            'status'     => 'required|in:pending,active,inactive',
            'image'      => 'nullable|image|max:5000|mimes:jpg,jpeg,png,gif,webp,svg',
        ];
    }

    public function saveUpdate(): void
    {
        $this->authorize('edit_student');
        $this->validate();

        $data = [
            'name'       => $this->name,
            'email'      => $this->email,
            'phone'      => $this->phone,
            'phone_key'  => $this->phone_key,
            'grade_id'   => $this->grade_id,
            'section_id' => $this->section_id,
            'status'     => $this->status,
        ];

        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }
        $oldStatus = $this->student->status;

        $this->student->update($data);

        // Notify student about account status change
        if ($oldStatus !== $this->status && in_array($this->status, ['active', 'inactive'])) {
            try {
                \Illuminate\Support\Facades\Mail::to($this->student->email)
                    ->send(new \App\Mail\AccountStatusNotification($this->student, $this->status));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Failed to send account status email: ' . $e->getMessage());
            }
        }

        if ($this->image) {
            $this->student->addMedia($this->image->getRealPath())->toMediaCollection('image');
        }

        $this->modalUpdate = false;
        $this->dispatch('render')->component(StudentData::class);
        $this->success(__('lang.updated_successfully', ['attribute' => __('lang.student')]));
    }

    public function render(): View
    {
        return view('livewire.dashboard.student.update-student');
    }

    public function resetError(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
