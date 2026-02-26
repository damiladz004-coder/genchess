<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMessageReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public array $payload
    ) {}

    public function build()
    {
        return $this->subject('New Contact Message: ' . $this->payload['subject'])
            ->replyTo($this->payload['email'], $this->payload['name'])
            ->view('emails.contact-message-received');
    }
}
