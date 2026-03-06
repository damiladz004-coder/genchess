<h2>Request Approved</h2>

<p>Hello {{ $requestData->contact_person }},</p>

<p>
    Your {{ strtolower((string) $requestData->program_type) === 'home' ? 'home' : 'community' }}
    chess program request has been approved by Genchess.
</p>

<p>
    Our management team will contact you soon to confirm agreement details,
    finalize appointment date/time, and agree on your operational start plan.
</p>

@if($requestData->consultation_needed)
    <p>
        Your consultation preference:
        {{ ucfirst((string) $requestData->meeting_type) ?: 'Not specified' }},
        {{ $requestData->meeting_date ? \Illuminate\Support\Carbon::parse($requestData->meeting_date)->format('F j, Y') : 'Date not set' }}
        at {{ $requestData->meeting_time ?: 'Time not set' }}.
    </p>
@endif

<p>Regards,<br>Genchess Educational Services</p>

