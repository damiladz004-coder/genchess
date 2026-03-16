<h2>Consultation Meeting Scheduled</h2>

<p>Hello {{ $communityConsultation->name }},</p>

<p>Your consultation with Genchess has been scheduled.</p>

<ul>
    <li>Meeting Type: {{ str($communityConsultation->meeting_type)->replace('_', ' ')->title() }}</li>
    <li>Date: {{ optional($communityConsultation->scheduled_at)->format('F j, Y') ?? 'Not set' }}</li>
    <li>Time: {{ optional($communityConsultation->scheduled_at)->format('g:i A') ?? 'Not set' }}</li>
    @if($communityConsultation->meeting_link)
        <li>Join using this link: {{ $communityConsultation->meeting_link }}</li>
    @endif
    @if($communityConsultation->meeting_id)
        <li>Meeting ID: {{ $communityConsultation->meeting_id }}</li>
    @endif
    @if($communityConsultation->meeting_passcode)
        <li>Meeting Passcode: {{ $communityConsultation->meeting_passcode }}</li>
    @endif
    @if($communityConsultation->meeting_location)
        <li>Meeting Location: {{ $communityConsultation->meeting_location }}</li>
    @endif
</ul>

<p>Regards,<br>Genchess Educational Services</p>
