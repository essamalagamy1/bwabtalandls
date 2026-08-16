<?php

namespace App\Livewire\Dashboard\Semester;

use App\Models\Semester;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class CreateSemester extends Component
{
    use Toast, WithFileUploads;

    public bool $modalAdd = false;
    public $name;
    public $grade_id;
    public bool $is_active = true;
    public $image;
    public $all_grades;

    public function render()
    {
        return view('livewire.dashboard.semester.create-semester');
    }

    public function rules(): array
    {
        return [
            'name'      => 'required|string|max:255|unique:semesters,name',
            'grade_id'  => 'required|exists:grades,id',
            'is_active' => 'boolean',
            'image'     => 'nullable|image|max:5000|mimes:jpg,jpeg,png,gif,webp,svg',
        ];
    }

    public function saveAdd(): void
    {
        $this->authorize('create_semester');
        $this->validate();

        $semester = Semester::create([
            'name'      => $this->name,
            'grade_id'  => $this->grade_id,
            'is_active' => $this->is_active,
        ]);

        if ($this->image) {
            $semester->addMedia($this->image->getRealPath())->toMediaCollection('image');
        }

        $this->modalAdd = false;
        $this->dispatch('render')->component(SemesterData::class);
        $this->success(__('lang.created_successfully', ['attribute' => __('lang.semester')]));
    }

    public function resetData(): void
    {
        $this->reset(['name', 'grade_id', 'image']);
        $this->is_active = true;
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
