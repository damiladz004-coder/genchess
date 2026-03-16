<?php

namespace App\Mail;

use App\Models\CommunityConsultation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CommunityConsultationConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public CommunityConsultation $communityConsultation)
    {
    }

    public function build()
    {
        return $this->subject('Genchess Consultation Request Received')
            ->view('emails.community-consultation-confirmation');
    }
}
