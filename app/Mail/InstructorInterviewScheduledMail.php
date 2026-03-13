<?php

namespace App\Mail;

use App\Models\InstructorScreening;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InstructorInterviewScheduledMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public InstructorScreening $screening,
        public string $stage
    ) {
    }

    public function build()
    {
        return $this->subject("Genchess {$this->stage} Interview Schedule")
            ->view('emails.instructor-interview-scheduled');
    }
}
