<?php

namespace App\Mail;

use App\Models\School;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SchoolAdminInvite extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public School $school,
        public string $temporaryPassword
    ) {
    }

    public function build()
    {
        return $this->subject('Your Genchess School Admin Account Is Ready')
            ->view('emails.school-admin-invite');
    }
}

