<?php

namespace App\Livewire\Dashboard\Ticket;

use App\Models\Ticket;
use App\Models\TicketReply;
use App\Notifications\TicketReplyNotification;
use App\Notifications\TicketStatusUpdatedNotification;
use Illuminate\Support\Facades\Notification;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('تفاصيل التذكرة')]
class ShowTicket extends Component
{
    use WithFileUploads;

    public Ticket $ticket;
    public string $replyMessage = '';
    public $attachment;

    public function mount(Ticket $ticket)
    {
        $this->authorize('show_ticket');
        $this->ticket = $ticket->load(['user', 'assignedAdmin', 'replies.user.media', 'replies.media']);
        
        // Mark as in progress if open and viewed by admin
        if ($this->ticket->status === 'open' && auth()->user()->hasPermissionTo('edit_ticket')) {
            $this->ticket->update([
                'status' => 'in_progress',
                'assigned_admin_id' => auth()->id(),
            ]);
            
            // Notify user
            $this->ticket->user->notify(new TicketStatusUpdatedNotification($this->ticket, 'قيد المراجعة'));
        }
    }

    public function changeStatus(string $status): void
    {
        $this->authorize('edit_ticket');
        
        $this->ticket->update(['status' => $status]);
        
        $statusNames = ['open' => 'مفتوحة', 'in_progress' => 'قيد المراجعة', 'closed' => 'مغلقة'];
        $this->ticket->user->notify(new TicketStatusUpdatedNotification($this->ticket, $statusNames[$status]));
        
        $this->dispatch('success', 'تم تغيير حالة التذكرة');
    }

    public function sendReply(): void
    {
        $this->validate([
            'replyMessage' => 'required|string',
            'attachment' => 'nullable|image|max:5120', // Max 5MB
        ]);

        $reply = $this->ticket->replies()->create([
            'user_id' => auth()->id(),
            'message' => $this->replyMessage,
        ]);

        if ($this->attachment) {
            $reply->addMedia($this->attachment)->toMediaCollection('attachments');
        }

        // Notify user
        $this->ticket->user->notify(new TicketReplyNotification($this->ticket, auth()->user()->name));

        $this->reset(['replyMessage', 'attachment']);
        $this->ticket->load(['replies.user.media', 'replies.media']);
        $this->dispatch('success', 'تم إرسال الرد بنجاح');
    }

    public function render()
    {
        return view('livewire.dashboard.ticket.show-ticket');
    }
}
