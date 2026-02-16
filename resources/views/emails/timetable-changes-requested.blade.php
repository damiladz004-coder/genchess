@php
    $settings = \App\Models\Setting::whereIn('key', ['organization_name', 'support_email', 'support_phone'])
        ->get()
        ->keyBy('key');
    $orgName = $settings['organization_name']->value ?? 'Genchess';
    $supportEmail = $settings['support_email']->value ?? '';
    $supportPhone = $settings['support_phone']->value ?? '';
@endphp

<h2>Timetable Changes Requested</h2>
<p>Your timetable entry requires changes by {{ $orgName }}.</p>

<p><strong>School:</strong> {{ $timetable->school->school_name ?? 'N/A' }}</p>
<p><strong>Class:</strong> {{ $timetable->classroom->name ?? 'N/A' }}</p>
<p><strong>Day:</strong> {{ ucfirst($timetable->day_of_week) }}</p>
<p><strong>Time:</strong> {{ $timetable->start_time ?? '-' }} - {{ $timetable->end_time ?? '-' }}</p>
<p><strong>Location:</strong> {{ $timetable->location ?? '-' }}</p>

@if($timetable->review_comment)
    <p><strong>Comment:</strong> {{ $timetable->review_comment }}</p>
@endif

@if($supportEmail || $supportPhone)
    <p>Support: {{ trim($supportEmail . ' ' . $supportPhone) }}</p>
@endif
