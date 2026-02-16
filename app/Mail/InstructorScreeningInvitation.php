<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InstructorScreeningInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $candidateName,
        public string $interviewMode
    ) {}

    public function build()
    {
        return $this->subject('Genchess Instructor Interview Invitation')
            ->view('emails.instructor-screening-invitation');
    }
}
