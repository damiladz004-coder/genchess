<h2>Consultation Scheduled</h2>
<p>Hello {{ $schoolRequest->contact_person }},</p>
<p>Your consultation for the {{ ucfirst($schoolRequest->program_type) }} program has been scheduled.</p>
<ul>
    <li>Date: {{ $schoolRequest->meeting_date ? \Illuminate\Support\Carbon::parse($schoolRequest->meeting_date)->format('F j, Y') : 'Not set' }}</li>
    <li>Time: {{ $schoolRequest->meeting_time ? \Illuminate\Support\Carbon::parse($schoolRequest->meeting_time)->format('g:i A') : 'Not set' }}</li>
    <li>Meeting type: {{ ucfirst($schoolRequest->meeting_type ?: 'online') }}</li>
    <li>Meeting link: {{ $schoolRequest->consultation_link ?: 'Not provided' }}</li>
    <li>Meeting ID: {{ $schoolRequest->consultation_meeting_id ?: 'Not provided' }}</li>
    <li>Passcode: {{ $schoolRequest->consultation_passcode ?: 'Not provided' }}</li>
</ul>
<p>Regards,<br>Genchess Educational Services</p>
