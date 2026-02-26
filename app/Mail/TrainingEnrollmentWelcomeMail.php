<?php

namespace App\Mail;

use App\Models\TrainingInvoice;
use App\Models\TrainingPayment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TrainingEnrollmentWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public TrainingPayment $payment,
        public TrainingInvoice $invoice
    ) {
    }

    public function build(): self
    {
        return $this
            ->subject('Welcome to Genchess Instructor Training')
            ->view('emails.training-enrollment-welcome');
    }
}

