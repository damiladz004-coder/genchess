<?php

namespace App\Mail;

use App\Models\SchoolTimetable;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TimetableSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public SchoolTimetable $timetable)
    {}

    public function build()
    {
        return $this->subject('School Timetable Submitted')
            ->view('emails.timetable-submitted');
    }
}
