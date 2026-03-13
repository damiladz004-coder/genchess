<?php

namespace App\Mail;

use App\Models\SchoolRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SchoolPortalAccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public SchoolRequest $schoolRequest,
        public string $onboardingUrl
    ) {
    }

    public function build()
    {
        return $this->subject('Genchess School Portal Access Link')
            ->view('emails.school-portal-access');
    }
}
