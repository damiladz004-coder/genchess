<h2>Genchess Enrollment Approved</h2>

<p>Hello {{ $schoolRequest->contact_person }},</p>

<p>
    Your enrollment request for <strong>{{ $schoolRequest->school_name }}</strong> has been approved.
</p>

@if($tempPassword)
    <p>
        A school admin account has been created for you:
    </p>
    <ul>
        <li>Email: {{ $schoolRequest->email }}</li>
        <li>Temporary Password: {{ $tempPassword }}</li>
    </ul>
    <p>
        Next steps:
    </p>
    <ol>
        <li>Check your inbox for the email verification message from Genchess and verify your email address.</li>
        <li>Login at <a href="{{ url('/login') }}">{{ url('/login') }}</a>.</li>
        <li>You will be required to change this temporary password immediately.</li>
    </ol>
@else
    <p>
        Your existing school admin account has been linked to your school profile.
    </p>
    <p>
        If your email is not yet verified, please use the verification email sent by Genchess before accessing your dashboard.
    </p>
@endif

<p>Regards,<br>Genchess Educational Services</p>

