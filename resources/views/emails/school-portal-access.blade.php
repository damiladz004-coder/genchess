<h2>School Portal Access</h2>
<p>Hello {{ $schoolRequest->contact_person }},</p>
<p>Your school request for <strong>{{ $schoolRequest->school_name }}</strong> has been approved.</p>
<p>
    Complete your school portal onboarding here:
    <a href="{{ $onboardingUrl }}">{{ $onboardingUrl }}</a>
</p>
<p>After registration you can sign in to the school dashboard.</p>
<p>Regards,<br>Genchess Educational Services</p>
