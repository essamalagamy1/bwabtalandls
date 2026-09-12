<?php

namespace App\Livewire\Dashboard\Student;

use App\Mail\AccountStatusNotification;
use App\Models\AcademicEnrollment;
use App\Models\Grade;
use App\Models\Section;
use App\Models\Stage;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class UpdateStudent extends Component
{
    use Toast, WithFileUploads;

    public bool $modalUpdate = false;

    public User $student;

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

    public $status;

    public $image;

    // Parent fields (optional)
    public $parent_email;

    public $parent_password;

    public $parent_password_confirmation;

    public $parent_name;

    public $parent_phone;

    public $parent_phone_key;

    // Existing parent info (read-only display)
    public ?int $existing_parent_id = null;

    public ?string $existing_parent_name = null;

    public ?string $existing_parent_email = null;

    public $all_stages = [];

    public $all_grades = [];

    public $all_sections = [];

    public function mount(): void
    {
        $this->name = $this->student->name;
        $this->email = $this->student->email;
        $this->phone = $this->student->phone;
        $this->phone_key = $this->student->phone_key;
        $this->grade_id = $this->student->grade_id;
        $this->section_id = $this->student->section_id;
        $this->status = $this->student->status;

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

        // Load current parent info
        if ($this->student->parent_id) {
            $parent = User::find($this->student->parent_id);
            if ($parent) {
                $this->existing_parent_id = $parent->id;
                $this->existing_parent_name = $parent->name;
                $this->existing_parent_email = $parent->email;
                $this->parent_email = $parent->email;
                $this->parent_name = $parent->name;
                $this->parent_phone = $parent->phone;
                $this->parent_phone_key = $parent->phone_key;
            }
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
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$this->student->id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'phone_key' => 'required|string|max:5',
            'stage_id' => 'required|exists:stages,id',
            'grade_id' => 'required|exists:grades,id',
            'section_id' => 'nullable|exists:sections,id',
            'status' => 'required|in:pending,active,inactive',
            'image' => 'nullable|image|max:5000|mimes:jpg,jpeg,png,gif,webp,svg',

            'parent_name' => 'nullable|string|max:255',
            'parent_phone' => 'nullable|string|max:20',
            'parent_phone_key' => 'nullable|string|max:5',
        ];

        // If a new parent_email is entered (or changed), validate it
        if ($this->parent_email && $this->parent_email !== $this->existing_parent_email) {
            $rules['parent_email'] = 'required|email|max:255';
            // Password required only if creating a brand-new parent
            if (! User::where('email', $this->parent_email)->whereHas('roles', fn ($q) => $q->where('name', 'parent'))->exists()) {
                $rules['parent_password'] = 'required|string|min:8|confirmed';
            } else {
                $rules['parent_password'] = 'nullable|string|min:8|confirmed';
            }
        } elseif ($this->parent_email) {
            $rules['parent_email'] = 'nullable|email|max:255';
            $rules['parent_password'] = 'nullable|string|min:8|confirmed';
        } else {
            $rules['parent_email'] = 'nullable|email|max:255';
            $rules['parent_password'] = 'nullable';
        }

        return $rules;
    }

    public function saveUpdate(): void
    {
        $this->authorize('edit_student');
        $this->validate();

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'phone_key' => $this->phone_key,
            'grade_id' => $this->grade_id,
            'section_id' => $this->section_id,
            'status' => $this->status,
        ];

        if (! empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        // Handle parent
        if ($this->parent_email) {
            $data['parent_id'] = $this->createOrUpdateParent();
        } elseif (! $this->parent_email && $this->existing_parent_id) {
            // Parent email cleared — unlink
            $data['parent_id'] = null;
        }

        $oldStatus = $this->student->status;
        $oldGradeId = $this->student->grade_id;
        $oldSectionId = $this->student->section_id;
        $this->student->update($data);

        // Handle academic enrollment history
        if ($oldGradeId != $this->grade_id || $oldSectionId != $this->section_id) {
            $this->student->currentEnrollment()->update([
                'is_current' => false,
                'ended_at' => now(),
            ]);

            AcademicEnrollment::create([
                'user_id' => $this->student->id,
                'grade_id' => $this->grade_id,
                'section_id' => $this->section_id,
                'is_current' => true,
                'notes' => 'Grade or Section updated',
            ]);
        }

        // Notify student about account status change
        if ($oldStatus !== $this->status && in_array($this->status, ['active', 'inactive'])) {
            try {
                Mail::to($this->student->email)
                    ->send(new AccountStatusNotification($this->student, $this->status));
            } catch (\Exception $e) {
                Log::error('Failed to send account status email: '.$e->getMessage());
            }
        }

        if ($this->image) {
            $this->student->addMedia($this->image->getRealPath())->toMediaCollection('image');
        }

        $this->modalUpdate = false;
        $this->dispatch('render')->component(StudentData::class);
        $this->success(__('lang.updated_successfully', ['attribute' => __('lang.student')]));
    }

    /**
     * Find existing parent by email or create a new one.
     * If same parent email and we have new password/name, update existing parent.
     */
    private function createOrUpdateParent(): int
    {
        // Check if it's the same parent as before
        if ($this->existing_parent_id && $this->parent_email === $this->existing_parent_email) {
            $parent = User::find($this->existing_parent_id);
            if ($parent) {
                $updateData = [];
                if ($this->parent_name && $this->parent_name !== $parent->name) {
                    $updateData['name'] = $this->parent_name;
                }
                if ($this->parent_phone) {
                    $updateData['phone'] = $this->parent_phone;
                    $updateData['phone_key'] = $this->parent_phone_key;
                }
                if (! empty($this->parent_password)) {
                    $updateData['password'] = Hash::make($this->parent_password);
                }
                if ($updateData) {
                    $parent->update($updateData);
                }

                return $parent->id;
            }
        }

        // Look for existing parent with this email
        $existingParent = User::where('email', $this->parent_email)
            ->whereHas('roles', fn ($q) => $q->where('name', 'parent'))
            ->first();

        if ($existingParent) {
            // Update name/phone if provided
            $updateData = [];
            if ($this->parent_name) {
                $updateData['name'] = $this->parent_name;
            }
            if ($this->parent_phone) {
                $updateData['phone'] = $this->parent_phone;
                $updateData['phone_key'] = $this->parent_phone_key;
            }
            if (! empty($this->parent_password)) {
                $updateData['password'] = Hash::make($this->parent_password);
            }
            if ($updateData) {
                $existingParent->update($updateData);
            }

            return $existingParent->id;
        }

        // Create new parent
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
