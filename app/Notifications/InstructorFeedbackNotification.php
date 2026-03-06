<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class InstructorFeedbackNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected string $context,
        protected string $feedback
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'instructor_feedback',
            'title' => 'Instructor feedback received',
            'message' => "{$this->context}: {$this->feedback}",
        ];
    }
}

