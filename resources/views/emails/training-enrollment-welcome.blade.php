@php
    $amount = number_format($payment->amount_kobo / 100, 2);
@endphp
<p>Hello {{ $payment->user->name }},</p>

<p>Welcome to Genchess Instructor Training. Your payment has been confirmed.</p>

<p><strong>Course:</strong> {{ $payment->course->title }}</p>
<p><strong>Invoice:</strong> {{ $invoice->invoice_number }}</p>
<p><strong>Amount Paid:</strong> NGN {{ $amount }}</p>

<p><strong>Login:</strong> <a href="{{ route('login') }}">{{ route('login') }}</a></p>
<p><strong>Course Workspace:</strong> <a href="{{ route('instructor.training.index') }}">{{ route('instructor.training.index') }}</a></p>

<p>Community group link: https://chat.whatsapp.com/genchess-community</p>

<p>Thank you,</p>
<p>Genchess Team</p>

