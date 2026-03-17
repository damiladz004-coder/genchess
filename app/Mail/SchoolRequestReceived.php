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
        return $this->subject('Genchess School Registration Received')
            ->view('emails.school-request-received');
    }
}
