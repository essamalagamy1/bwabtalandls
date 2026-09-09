<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class TicketStatusUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Ticket $ticket, public string $statusText) {}

    public function via(object $notifiable): array
    {
        return ['database', WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        $url = $notifiable->hasRole('admin') ? route('dashboard.tickets.show', $this->ticket) : route('user.tickets.show', $this->ticket);

        return (new WebPushMessage)
            ->title('تحديث حالة التذكرة')
            ->body("تم تغيير حالة تذكرتك ({$this->ticket->subject}) إلى: {$this->statusText}")
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
            'title' => 'تحديث حالة التذكرة',
            'body' => "تم تغيير حالة تذكرتك ({$this->ticket->subject}) إلى: {$this->statusText}",
            'url' => $notifiable->hasRole('admin') ? route('dashboard.tickets.show', $this->ticket) : route('user.tickets.show', $this->ticket),
        ];
    }
}
