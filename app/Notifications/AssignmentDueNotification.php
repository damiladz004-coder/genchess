<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AssignmentDueNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected string $assignmentTitle,
        protected string $courseTitle,
        protected ?string $dueAt = null
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $message = "{$this->assignmentTitle} is available in {$this->courseTitle}.";
        if ($this->dueAt) {
            $message .= " Due: {$this->dueAt}.";
        }

        return [
            'type' => 'assignment_due',
            'title' => 'New assignment published',
            'message' => $message,
        ];
    }
}
