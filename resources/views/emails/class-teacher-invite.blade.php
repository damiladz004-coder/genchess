@php
    $loginUrl = url('/login');
    $settings = \App\Models\Setting::whereIn('key', ['organization_name', 'support_email', 'support_phone'])
        ->get()
        ->keyBy('key');
    $orgName = $settings['organization_name']->value ?? 'Genchess';
    $supportEmail = $settings['support_email']->value ?? '';
    $supportPhone = $settings['support_phone']->value ?? '';
@endphp

<h2>Welcome to {{ $orgName }}</h2>
<p>Hello {{ $classTeacher->name }},</p>
<p>Your class teacher account has been created.</p>

<p><strong>Login Email:</strong> {{ $classTeacher->email }}</p>
<p><strong>Temporary Password:</strong> {{ $temporaryPassword }}</p>

<p>You will be required to change your password on first login.</p>
<p><a href="{{ $loginUrl }}">Log in</a></p>

@if($supportEmail || $supportPhone)
    <p>Support: {{ trim($supportEmail . ' ' . $supportPhone) }}</p>
@endif
