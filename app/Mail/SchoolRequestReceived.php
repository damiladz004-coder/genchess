<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SchoolRequestReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public array $requestData
    ) {
    }

    public function build()
    {
        return $this->subject('We Received Your Genchess Program Request')
            ->view('emails.school-request-received');
    }
}

