<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class NewTicketNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Ticket $ticket) {}

    public function via(object $notifiable): array
    {
        return ['database', WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        $url = route('dashboard.tickets.show', $this->ticket);

        return (new WebPushMessage)
            ->title('تذكرة جديدة')
            ->body("تم فتح تذكرة جديدة: {$this->ticket->subject}")
            ->data(['url' => $url, 'sound' => '/sounds/notification.mp3'])
            ->icon('/logo.png')
            ->badge('/favicon.svg')
            ->vibrate([100, 50, 100])
            ->dir('rtl')
            ->lang('ar');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'تذكرة جديدة',
            'body' => "تم فتح تذكرة جديدة: {$this->ticket->subject}",
            'url' => route('dashboard.tickets.show', $this->ticket),
        ];
    }
}
