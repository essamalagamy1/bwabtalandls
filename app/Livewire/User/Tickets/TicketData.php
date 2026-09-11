<?php

namespace App\Livewire\User\Tickets;

use App\Jobs\SendNewTicketNotificationJob;
use App\Models\Ticket;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('تذاكري')]
class TicketData extends Component
{
    use WithPagination;

    public string $search = '';

    public string $statusFilter = '';

    // Create Ticket Fields
    public bool $modalAdd = false;

    public string $subject = '';

    public string $type = 'problem';

    public function updated($property): void
    {
        if (in_array($property, ['search', 'statusFilter'])) {
            $this->resetPage();
        }
    }

    public function saveAdd(): void
    {
        $this->validate([
            'subject' => 'required|string|max:255',
            'type' => 'required|in:problem,suggestion,inquiry',
        ]);

        $ticket = auth()->user()->tickets()->create([
            'subject' => $this->subject,
            'type' => $this->type,
            'status' => 'open',
            'priority' => 'medium', // Default for user created
        ]);

        // Send notification job
        SendNewTicketNotificationJob::dispatch($ticket);

        $this->modalAdd = false;
        $this->reset(['subject', 'type']);
        $this->dispatch('success', 'تم إنشاء التذكرة بنجاح');
    }

    public function render()
    {
        $tickets = auth()->user()->tickets()
            ->when($this->search, fn ($q) => $q->where('subject', 'like', '%'.$this->search.'%'))
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->paginate(10);

        $headers = [
            ['key' => 'id', 'label' => '#'],
            ['key' => 'subject', 'label' => 'العنوان'],
            ['key' => 'type', 'label' => 'النوع'],
            ['key' => 'status', 'label' => 'الحالة'],
            ['key' => 'created_at', 'label' => 'تاريخ الإنشاء'],
        ];

        return view('livewire.user.tickets.ticket-data', [
            'tickets' => $tickets,
            'headers' => $headers,
        ]);
    }
}
