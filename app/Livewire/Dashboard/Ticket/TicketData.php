<?php

namespace App\Livewire\Dashboard\Ticket;

use App\Models\Ticket;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('تذاكر الدعم')]
class TicketData extends Component
{
    use WithPagination;

    public string $search = '';

    public string $statusFilter = '';

    public string $typeFilter = '';

    public string $priorityFilter = '';

    public string $roleFilter = '';

    public function updated($property): void
    {
        if (in_array($property, ['search', 'statusFilter', 'typeFilter', 'priorityFilter', 'roleFilter'])) {
            $this->resetPage();
        }
    }

    public function deleteTicket(Ticket $ticket): void
    {
        $this->authorize('delete_ticket');
        $ticket->delete();
        $this->dispatch('success', 'تم حذف التذكرة بنجاح');
    }

    public function render()
    {
        $this->authorize('show_ticket');

        $tickets = Ticket::with(['user.roles', 'assignedAdmin'])
            ->when($this->search, fn ($q) => $q->where('subject', 'like', '%'.$this->search.'%'))
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->when($this->typeFilter, fn ($q) => $q->where('type', $this->typeFilter))
            ->when($this->priorityFilter, fn ($q) => $q->where('priority', $this->priorityFilter))
            ->when($this->roleFilter, function ($q) {
                $q->whereHas('user.roles', fn ($r) => $r->where('name', $this->roleFilter));
            })
            ->latest()
            ->paginate(15);

        $headers = [
            ['key' => 'id', 'label' => '#'],
            ['key' => 'subject', 'label' => 'العنوان'],
            ['key' => 'user', 'label' => 'صاحب التذكرة', 'sortable' => false],
            ['key' => 'type', 'label' => 'النوع'],
            ['key' => 'status', 'label' => 'الحالة'],
            ['key' => 'created_at', 'label' => 'تاريخ الإنشاء'],
        ];

        return view('livewire.dashboard.ticket.ticket-data', [
            'tickets' => $tickets,
            'headers' => $headers,
        ]);
    }
}
