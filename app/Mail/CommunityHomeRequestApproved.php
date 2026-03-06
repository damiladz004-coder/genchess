<?php

namespace App\Mail;

use App\Models\SchoolRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CommunityHomeRequestApproved extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public SchoolRequest $requestData
    ) {
    }

    public function build()
    {
        return $this->subject('Your Genchess Request Has Been Approved')
            ->view('emails.community-home-approved');
    }
}

