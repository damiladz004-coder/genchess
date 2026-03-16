<h2>Appointment Request Received</h2>

<p>Hello {{ $communityConsultation->name }},</p>

<p>Thank you for contacting Genchess.</p>

<p>
    We have received your request regarding:
    <strong>{{ \App\Models\CommunityConsultation::purposeLabels()[$communityConsultation->purpose] ?? 'Your consultation request' }}</strong>
</p>

<p>
    Our team will review your request and contact you shortly to schedule a meeting.
</p>

<p>Regards,<br>Genchess Educational Services</p>
