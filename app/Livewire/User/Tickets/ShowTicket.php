<?php

namespace App\Livewire\User\Tickets;

use App\Models\Ticket;
use App\Notifications\TicketReplyNotification;
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
        // Ensure user owns this ticket
        if ($ticket->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $this->ticket = $ticket->load(['replies.user.media', 'replies.media', 'assignedAdmin']);
    }

    public function sendReply(): void
    {
        if ($this->ticket->status === 'closed') {
            $this->addError('replyMessage', 'لا يمكنك الرد على تذكرة مغلقة.');
            return;
        }

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

        // Notify assigned admin or all admins with permission
        if ($this->ticket->assignedAdmin) {
            $this->ticket->assignedAdmin->notify(new TicketReplyNotification($this->ticket, auth()->user()->name));
        } else {
            $admins = \App\Models\User::role('admin')->permission('show_ticket')->get();
            if ($admins->isNotEmpty()) {
                \Illuminate\Support\Facades\Notification::send($admins, new TicketReplyNotification($this->ticket, auth()->user()->name));
            }
        }

        $this->reset(['replyMessage', 'attachment']);
        $this->ticket->load(['replies.user.media', 'replies.media']);
        $this->dispatch('success', 'تم إرسال الرد بنجاح');
    }

    public function render()
    {
        return view('livewire.user.tickets.show-ticket');
    }
}
