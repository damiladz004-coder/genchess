<?php

namespace App\Mail;

use App\Models\SchoolTimetable;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TimetableChangesRequested extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public SchoolTimetable $timetable)
    {}

    public function build()
    {
        return $this->subject('Timetable Changes Requested')
            ->view('emails.timetable-changes-requested');
    }
}
