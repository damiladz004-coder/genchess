<?php

namespace App\Mail;

use App\Models\SchoolRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SchoolEnrollmentApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $schoolRequest;
    public $tempPassword;

    public function __construct(SchoolRequest $schoolRequest, ?string $tempPassword = null)
    {
        $this->schoolRequest = $schoolRequest;
        $this->tempPassword = $tempPassword;
    }

    public function build()
    {
        return $this->subject('Your Genchess Enrollment Has Been Approved')
            ->view('emails.school-approved');
    }
}
