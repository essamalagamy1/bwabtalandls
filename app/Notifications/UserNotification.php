<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class UserNotification extends Notification
{
    use Queueable;

    public function __construct(public string $title, public string $body, public $url = null)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database', WebPushChannel::class];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->title)
            ->greeting("هلا والله {$notifiable->name}،")
            ->line($this->body)
            ->action('عرض التفاصيل', $this->url ?: route('dashboard'))
            ->salutation('مع خالص التحية،');
    }

    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title($this->title)
            ->body($this->body)
            ->data(['url' => $this->url ?: route('dashboard'), 'sound' => '/sounds/notification.mp3'])
            ->icon('/logo.png')
            ->badge('/favicon.svg')
            ->vibrate([100, 50, 100])
            ->tag('notification-tag')
            ->options(['TTL' => 1000])
            ->dir('rtl')
            ->lang('ar')
            ->requireInteraction();
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'body'  => $this->body,
            'url'   => $this->url ?: route('dashboard'),
        ];
    }
}
