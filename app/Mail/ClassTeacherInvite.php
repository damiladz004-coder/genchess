<?php

namespace App\Mail;

use App\Models\ClassTeacher;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClassTeacherInvite extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public ClassTeacher $classTeacher,
        public string $temporaryPassword
    ) {}

    public function build()
    {
        return $this->subject('Your Genchess Class Teacher Account')
            ->view('emails.class-teacher-invite');
    }
}
