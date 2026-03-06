<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LiveClassScheduledNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected string $title,
        protected string $startTime
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'live_class_scheduled',
            'title' => 'Live class scheduled',
            'message' => "{$this->title} starts at {$this->startTime}.",
        ];
    }
}

