<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewLessonReleasedNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected string $lessonTitle,
        protected string $courseTitle
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_lesson_released',
            'title' => 'New lesson released',
            'message' => "{$this->lessonTitle} has been added to {$this->courseTitle}.",
        ];
    }
}

