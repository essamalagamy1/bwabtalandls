<?php

namespace App\Livewire\Dashboard\Student;

use App\Models\Grade;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

#[Title('students')]
#[Lazy]
class StudentData extends Component
{
    use Toast, WithPagination;

    public function placeholder(): View
    {
        return view('livewire.placeholders.page-loading');
    }

    public $all_stages = [];
    public $all_grades = [];
    public $all_semesters = [];
    public $all_weeks = [];
    
    public $search_name;
    
    #[Url]
    public $search_stage_id;
    #[Url]
    public $search_grade_id;
    #[Url]
    public $search_semester_id;
    #[Url]
    public $search_week_id;
    
    public $search_status = '';

    public function updatedSearchStageId($value)
    {
        $this->search_grade_id = null;
        $this->search_semester_id = null;
        $this->search_week_id = null;
        $this->loadGrades();
        $this->loadSemesters();
        $this->loadWeeks();
    }

    public function updatedSearchGradeId($value)
    {
        $this->search_semester_id = null;
        $this->search_week_id = null;
        $this->loadSemesters();
        $this->loadWeeks();
    }

    public function updatedSearchSemesterId($value)
    {
        $this->search_week_id = null;
        $this->loadWeeks();
    }

    public function loadStages() {
        $this->all_stages = \App\Models\Stage::where('is_active', true)->get(['id', 'name'])->toArray();
    }

    public function loadGrades() {
        $query = \App\Models\Grade::with('stage:id,name')->where('is_active', true);
        if ($this->search_stage_id) {
            $query->where('stage_id', $this->search_stage_id);
        }
        $this->all_grades = $query->get(['id', 'name', 'stage_id'])
            ->map(function ($grade) {
                return [
                    'id' => $grade->id,
                    'name' => $grade->name,
                    'full_path_name' => $grade->stage?->name ?? ''
                ];
            })->toArray();
    }

    public function loadSemesters() {
        $query = \App\Models\Semester::with('grade.stage')->where('is_active', true);
        if ($this->search_grade_id) {
            $query->where('grade_id', $this->search_grade_id);
        } elseif ($this->search_stage_id) {
            $query->whereHas('grade', fn($q) => $q->where('stage_id', $this->search_stage_id));
        }
        $this->all_semesters = $query->get(['id', 'name', 'grade_id'])
            ->map(function ($semester) {
                return [
                    'id' => $semester->id,
                    'name' => $semester->name,
                    'full_path_name' => ($semester->grade?->stage?->name ?? '') . ' - ' . ($semester->grade?->name ?? '')
                ];
            })->toArray();
    }

    public function loadWeeks() {
        $query = \App\Models\Week::with('semester.grade.stage')
            ->where('is_active', true)
            ->whereHas('semester', function ($q) {
                $q->where('is_active', true)
                  ->whereHas('grade', function ($q2) {
                      $q2->where('is_active', true)
                         ->whereHas('stage', function ($q3) {
                             $q3->where('is_active', true);
                         });
                  });
            });

        if ($this->search_semester_id) {
            $query->where('semester_id', $this->search_semester_id);
        } elseif ($this->search_grade_id) {
            $query->whereHas('semester', fn($q) => $q->where('grade_id', $this->search_grade_id));
        } elseif ($this->search_stage_id) {
            $query->whereHas('semester.grade', fn($q) => $q->where('stage_id', $this->search_stage_id));
        }
        
        $this->all_weeks = $query->get(['id', 'title as name', 'semester_id'])
            ->map(function ($week) {
                return [
                    'id' => $week->id,
                    'name' => $week->name,
                    'full_path_name' => ($week->semester?->grade?->stage?->name ?? '') . ' - ' . ($week->semester?->grade?->name ?? '') . ' - ' . ($week->semester?->name ?? '')
                ];
            })
            ->toArray();
    }

    public function mount(): void
    {
        $this->loadStages();
        $this->loadGrades();
        $this->loadSemesters();
        $this->loadWeeks();
        view()->share('breadcrumbs', $this->breadcrumbs());
    }

    public function breadcrumbs(): array
    {
        return [
            ['label' => __('lang.students'), 'icon' => 'o-users'],
        ];
    }

    public function getStudentQuery(): Builder
    {
        $gradeIds = null;
        if ($this->search_week_id) {
            $gradeIds = [\App\Models\Week::find($this->search_week_id)?->semester?->grade_id];
        } elseif ($this->search_semester_id) {
            $gradeIds = [\App\Models\Semester::find($this->search_semester_id)?->grade_id];
        } elseif ($this->search_grade_id) {
            $gradeIds = [$this->search_grade_id];
        } elseif ($this->search_stage_id) {
            $gradeIds = \App\Models\Grade::where('stage_id', $this->search_stage_id)->pluck('id')->toArray();
        }

        return User::role('student')
            ->when($this->search_name, fn(Builder $q) => $q->where(fn($q2) => $q2->where('name', 'like', "%{$this->search_name}%")->orWhere('email', 'like', "%{$this->search_name}%")))
            ->when($gradeIds !== null, fn(Builder $q) => $q->whereIn('grade_id', $gradeIds))
            ->when($this->search_status !== '', fn(Builder $q) => $q->where('status', $this->search_status));
    }

    public function getFiltersText(): string
    {
        $filters = [];
        if ($this->search_name) {
            $filters[] = __('lang.search') . ': ' . $this->search_name;
        }
        if ($this->search_stage_id) {
            $stage = collect($this->all_stages)->firstWhere('id', $this->search_stage_id);
            if ($stage) $filters[] = __('lang.stage') . ': ' . $stage['name'];
        }
        if ($this->search_grade_id) {
            $grade = collect($this->all_grades)->firstWhere('id', $this->search_grade_id);
            if ($grade) $filters[] = __('lang.grade') . ': ' . $grade['name'];
        }
        if ($this->search_semester_id) {
            $semester = collect($this->all_semesters)->firstWhere('id', $this->search_semester_id);
            if ($semester) $filters[] = __('lang.semester') . ': ' . $semester['name'];
        }
        if ($this->search_week_id) {
            $week = collect($this->all_weeks)->firstWhere('id', $this->search_week_id);
            if ($week) $filters[] = __('lang.week') . ': ' . $week['name'];
        }
        if ($this->search_status !== '') {
            $statusLabel = $this->search_status === 'active' ? __('lang.active') : ($this->search_status === 'inactive' ? __('lang.inactive') : __('lang.pending'));
            $filters[] = __('lang.status') . ': ' . $statusLabel;
        }

        return count($filters) > 0 ? implode(' | ', $filters) : 'الكل';
    }

    public function exportExcel()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\StudentsExport($this->getStudentQuery(), $this->getFiltersText()), 'students.xlsx');
    }

    public function exportPdf()
    {
        $students = $this->getStudentQuery()->with('grade.stage')->get();
        $filtersText = $this->getFiltersText();
        $date = now()->format('Y-m-d H:i');
        // Since mPDF has autoScriptToLang for Arabic, we use it directly
        $pdf = \PDF::loadView('pdf.students', compact('students', 'filtersText', 'date'), [], [
            'mode' => 'utf-8',
            'format' => 'A4',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
        ]);
        return response()->streamDownload(fn () => print($pdf->output()), 'students.pdf');
    }

    #[On('render')]
    public function render(): View
    {
        $query = $this->getStudentQuery();
        
        // Clone for stats
        $statsQuery = clone $query;
        $data['total_students'] = $statsQuery->count();
        $data['active_students'] = (clone $statsQuery)->where('status', 'active')->count();
        $data['inactive_students'] = (clone $statsQuery)->where('status', 'inactive')->count();

        $data['students'] = $query->with('grade.stage')
            ->latest()
            ->paginate(10);

        return view('livewire.dashboard.student.student-data', $data);
    }

    public function delete($id): void
    {
        $this->authorize('delete_student');
        User::findOrFail($id)->delete();
        $this->success(__('lang.deleted_successfully', ['attribute' => __('lang.student')]));
    }
}
