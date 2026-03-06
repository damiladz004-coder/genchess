<h2>Genchess School Admin Account</h2>

<p>Hello {{ $school->contact_person }},</p>

<p>Your school <strong>{{ $school->school_name }}</strong> has been approved on Genchess.</p>

<p>A school admin account has been created for you:</p>
<ul>
    <li>Email: {{ $school->email }}</li>
    <li>Temporary Password: {{ $temporaryPassword }}</li>
</ul>

<p>Next steps:</p>
<ol>
    <li>Verify your email address using the verification email from Genchess.</li>
    <li>Login at <a href="{{ url('/login') }}">{{ url('/login') }}</a>.</li>
    <li>Change your temporary password immediately.</li>
</ol>

<p>Regards,<br>Genchess Educational Services</p>

