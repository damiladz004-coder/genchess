<?php

namespace App\Mail;

use App\Models\SchoolTimetable;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TimetableApproved extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public SchoolTimetable $timetable)
    {}

    public function build()
    {
        return $this->subject('Timetable Approved')
            ->view('emails.timetable-approved');
    }
}
