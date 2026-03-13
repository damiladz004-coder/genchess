@php
    $isStageTwo = $stage === 'Stage 2';
    $dateField = $isStageTwo ? $screening->stage_two_meeting_date : $screening->stage_three_meeting_date;
    $timeField = $isStageTwo ? $screening->stage_two_meeting_time : $screening->stage_three_meeting_time;
    $typeField = $isStageTwo ? $screening->stage_two_meeting_type : $screening->stage_three_meeting_type;
    $linkField = $isStageTwo ? $screening->stage_two_meeting_link : $screening->stage_three_meeting_link;
    $idField = $isStageTwo ? $screening->stage_two_meeting_id : $screening->stage_three_meeting_id;
    $passcodeField = $isStageTwo ? $screening->stage_two_passcode : $screening->stage_three_passcode;
@endphp

<h2>{{ $stage }} Interview Schedule</h2>
<p>Hello {{ $screening->name }},</p>
<p>Your {{ strtolower($stage) }} interview has been scheduled.</p>
<ul>
    <li>Meeting type: {{ ucfirst($typeField ?: $screening->interview_mode) }}</li>
    <li>Date: {{ $dateField ? \Illuminate\Support\Carbon::parse($dateField)->format('F j, Y') : 'To be confirmed' }}</li>
    <li>Time: {{ $timeField ? \Illuminate\Support\Carbon::parse($timeField)->format('g:i A') : 'To be confirmed' }}</li>
    <li>Meeting link: {{ $linkField ?: 'Not provided' }}</li>
    <li>Meeting ID: {{ $idField ?: 'Not provided' }}</li>
    <li>Passcode: {{ $passcodeField ?: 'Not provided' }}</li>
</ul>
<p>Regards,<br>Genchess Educational Services</p>
