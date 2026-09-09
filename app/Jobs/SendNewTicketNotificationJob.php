<?php

namespace App\Jobs;

use App\Models\Ticket;
use App\Models\User;
use App\Notifications\NewTicketNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Notification;

class SendNewTicketNotificationJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public Ticket $ticket) {}

    public function handle(): void
    {
        // Get all admins who have permission to show tickets
        $admins = User::role('admin')->permission('show_ticket')->get();

        if ($admins->isNotEmpty()) {
            Notification::send($admins, new NewTicketNotification($this->ticket));
        }
    }
}
