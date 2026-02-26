@php
    $serviceLabels = [
        'school' => 'School Enrollment',
        'community' => 'Communities',
        'home' => 'Home Lessons',
        'instructor' => 'Instructor Application',
        'products' => 'Products',
        'general' => 'General Inquiry',
    ];

    $service = $payload['service'] ?? null;
@endphp

<h2>New Contact Message</h2>

<p><strong>Name:</strong> {{ $payload['name'] }}</p>
<p><strong>Email:</strong> {{ $payload['email'] }}</p>
<p><strong>Phone:</strong> {{ $payload['phone'] ?: 'N/A' }}</p>
<p><strong>Service:</strong> {{ $service ? ($serviceLabels[$service] ?? ucfirst($service)) : 'Not specified' }}</p>
<p><strong>Subject:</strong> {{ $payload['subject'] }}</p>

<p><strong>Message:</strong></p>
<p>{{ $payload['message'] }}</p>
