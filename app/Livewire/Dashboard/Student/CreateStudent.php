<?php

namespace App\Livewire\Dashboard\Student;

use App\Models\Grade;
use App\Models\Section;
use App\Models\Stage;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class CreateStudent extends Component
{
    use Toast, WithFileUploads;

    public bool $modalAdd = false;

    // Student fields
    public $name;

    public $email;

    public $password;

    public $password_confirmation;

    public $phone;

    public $phone_key;

    public $stage_id;

    public $grade_id;

    public $section_id;

    public $status = 'active';

    public $image;

    // Parent fields (all optional)
    public $parent_email;

    public $parent_password;

    public $parent_password_confirmation;

    public $parent_name;

    public $parent_phone;

    public $parent_phone_key;

    public $all_stages = [];

    public $all_grades = [];

    public $all_sections = [];

    public function mount(): void
    {
        $this->all_stages = Stage::where('is_active', true)->get();
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

    public function render()
    {
        return view('livewire.dashboard.student.create-student');
    }

    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'phone_key' => 'required|string|max:5',
            'stage_id' => 'required|exists:stages,id',
            'grade_id' => 'required|exists:grades,id',
            'section_id' => 'nullable|exists:sections,id',
            'status' => 'required|in:pending,active,inactive',
            'image' => 'nullable|image|max:5000|mimes:jpg,jpeg,png,gif,webp,svg',

            // Parent (optional)
            'parent_name' => 'nullable|string|max:255',
            'parent_phone' => 'nullable|string|max:20',
            'parent_phone_key' => 'nullable|string|max:5',
        ];

        // If parent_email is provided, password becomes required
        if ($this->parent_email) {
            $rules['parent_email'] = 'required|email|max:255';
            $rules['parent_password'] = 'required|string|min:8|confirmed';
        } else {
            $rules['parent_email'] = 'nullable|email|max:255';
            $rules['parent_password'] = 'nullable';
        }

        return $rules;
    }

    public function saveAdd(): void
    {
        $this->authorize('create_student');
        $this->validate();

        // Handle parent account
        $parentId = null;
        if ($this->parent_email) {
            $parentId = $this->createOrFindParent();
        }

        $student = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'phone' => $this->phone,
            'phone_key' => $this->phone_key,
            'grade_id' => $this->grade_id,
            'section_id' => $this->section_id,
            'status' => $this->status,
            'email_verified_at' => now(),
            'parent_id' => $parentId,
        ]);

        $student->assignRole('student');

        if ($this->image) {
            $student->addMedia($this->image->getRealPath())->toMediaCollection('image');
        }

        $this->modalAdd = false;
        $this->dispatch('render')->component(StudentData::class);
        $this->success(__('lang.created_successfully', ['attribute' => __('lang.student')]));
    }

    /**
     * Find existing parent account by email or create a new one.
     * Supports multiple children under the same parent account.
     */
    private function createOrFindParent(): int
    {
        $existingParent = User::where('email', $this->parent_email)
            ->whereHas('roles', fn ($q) => $q->where('name', 'parent'))
            ->first();

        if ($existingParent) {
            return $existingParent->id;
        }

        $parentName = $this->parent_name ?: ('ولي أمر '.$this->name);

        $parent = User::create([
            'name' => $parentName,
            'email' => $this->parent_email,
            'password' => Hash::make($this->parent_password),
            'phone' => $this->parent_phone ?: null,
            'phone_key' => $this->parent_phone_key ?: null,
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $parent->assignRole('parent');

        return $parent->id;
    }

    public function resetData(): void
    {
        $this->reset([
            'name', 'email', 'password', 'password_confirmation',
            'phone', 'phone_key', 'stage_id', 'grade_id', 'section_id',
            'all_grades', 'all_sections', 'image',
            'parent_email', 'parent_password', 'parent_password_confirmation',
            'parent_name', 'parent_phone', 'parent_phone_key',
        ]);
        $this->status = 'active';
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
