<?php

namespace App\Mail;

use App\Models\SchoolRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CommunityConsultationScheduledMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public SchoolRequest $schoolRequest)
    {
    }

    public function build()
    {
        return $this->subject('Your Genchess Consultation Has Been Scheduled')
            ->view('emails.community-consultation-scheduled');
    }
}
