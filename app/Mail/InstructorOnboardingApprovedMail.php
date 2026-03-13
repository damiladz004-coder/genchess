<?php

namespace App\Mail;

use App\Models\InstructorScreening;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InstructorOnboardingApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public InstructorScreening $screening,
        public string $onboardingUrl
    ) {
    }

    public function build()
    {
        return $this->subject('Genchess Instructor Onboarding Link')
            ->view('emails.instructor-onboarding-approved');
    }
}
