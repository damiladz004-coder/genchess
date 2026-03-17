<h2>Your School Has Been Approved by Genchess</h2>
<p>Hello {{ $schoolRequest->contact_person }},</p>
<p>Congratulations! Your school registration for <strong>{{ $schoolRequest->school_name }}</strong> has been approved.</p>
<p>
    You can now create your login credentials and access the School Dashboard.
</p>
<p>
    Create your account here:
    <a href="{{ $onboardingUrl }}">{{ $onboardingUrl }}</a>
</p>
<p>This onboarding link is secure and time-limited for your protection.</p>
<p>Regards,<br>Genchess Educational Services</p>
