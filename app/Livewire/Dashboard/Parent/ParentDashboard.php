<?php

namespace App\Livewire\Dashboard\Parent;

use App\Models\ExamAttempt;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('لوحة ولي الأمر')]
class ParentDashboard extends Component
{
    /** The currently selected child (student) */
    public ?int $selectedChildId = null;

    /** @var User[] */
    public $children = [];

    public array $progressChart = [];

    public array $statusChart = [];

    public array $ticketStats = [];

    public function mount(): void
    {
        /** @var User $parent */
        $parent = auth()->user();

        if (! $parent->isParent()) {
            abort(403);
        }

        $this->children = $parent->children()
            ->with(['grade.stage', 'section'])
            ->where('status', '!=', 'inactive')
            ->get();

        // Default to first child
        if ($this->children->isNotEmpty() && ! $this->selectedChildId) {
            $this->selectedChildId = $this->children->first()->id;
        }

        $this->loadCharts();
        $this->loadTicketStats();
    }

    private function loadTicketStats(): void
    {
        $query = Ticket::where('user_id', auth()->id());

        $this->ticketStats = [
            'total' => (clone $query)->count(),
            'open' => (clone $query)->where('status', 'open')->count(),
            'in_progress' => (clone $query)->where('status', 'in_progress')->count(),
            'closed' => (clone $query)->where('status', 'closed')->count(),
        ];
    }

    public function updatedSelectedChildId(): void
    {
        $this->loadCharts();
    }

    private function loadCharts(): void
    {
        if (! $this->selectedChildId) {
            return;
        }

        $allAttempts = ExamAttempt::where('user_id', $this->selectedChildId)
            ->orderBy('created_at')
            ->get();

        $passedExams = $allAttempts->where('status', 'passed')->count();
        $failedExams = $allAttempts->where('status', 'failed')->count();
        $pendingExams = $allAttempts->whereNull('status')->count();

        $this->progressChart = [
            'type' => 'line',
            'data' => [
                'labels' => $allAttempts->map(fn ($a) => $a->created_at->format('M d'))->toArray(),
                'datasets' => [
                    [
                        'label' => 'الدرجة',
                        'data' => $allAttempts->pluck('total_score')->toArray(),
                        'borderColor' => '#25376F',
                        'tension' => 0.4,
                    ],
                ],
            ],
            'options' => [
                'scales' => [
                    'x' => ['title' => ['display' => true, 'text' => 'التاريخ']],
                    'y' => ['title' => ['display' => true, 'text' => 'الدرجة']],
                ],
            ],
        ];

        $this->statusChart = [
            'type' => 'doughnut',
            'data' => [
                'labels' => ['ناجح', 'راسب', 'قيد الإجراء'],
                'datasets' => [
                    [
                        'data' => [$passedExams, $failedExams, $pendingExams],
                        'backgroundColor' => ['#10b981', '#ef4444', '#f59e0b'],
                    ],
                ],
            ],
        ];
    }

    public function render(): View
    {
        /** @var User $parent */
        $parent = auth()->user();

        $child = $this->selectedChildId
            ? User::with(['grade.stage', 'section'])->find($this->selectedChildId)
            : null;

        $allAttempts = $child
            ? ExamAttempt::where('user_id', $child->id)->with('exam.week.semester')->latest()->get()
            : collect();

        $totalExamsTaken = $allAttempts->count();
        $averageScore = $totalExamsTaken > 0 ? round($allAttempts->avg('total_score'), 1) : 0;
        $passedCount = $allAttempts->where('status', 'passed')->count();
        $failedCount = $allAttempts->where('status', 'failed')->count();
        $childrenCount = $this->children->count();

        return view('livewire.dashboard.parent.parent-dashboard', compact(
            'child',
            'allAttempts',
            'totalExamsTaken',
            'averageScore',
            'passedCount',
            'failedCount',
            'childrenCount',
        ));
    }
}
