<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolRequest extends Model
{
    protected $fillable = [
        'school_name',
        'contact_person',
        'email',
        'phone',
        'program_type',
        'student_count',
        'message',
        'school_type',
        'class_system',
        'address_line',
        'city',
        'state',
        'applicant_type',
        'session_type',
        'physical_location',
        'children_count',
        'children_ages',
        'chess_level',
        'preferred_schedule',
        'parent_preferred_time',
        'organization_name',
        'participants_estimate',
        'age_group',
        'org_program_type',
        'consultation_needed',
        'meeting_type',
        'meeting_date',
        'meeting_time',
        'consultation_link',
        'consultation_meeting_id',
        'consultation_passcode',
        'consultation_invitation_sent_at',
        'consultation_whatsapp_sent_at',
        'consent',
        'status',
        'school_id',
        'portal_link_sent_at',
        'portal_whatsapp_sent_at',
        'portal_onboarded_at',
    ];

    protected function casts(): array
    {
        return [
            'meeting_date' => 'date',
            'meeting_time' => 'datetime:H:i',
            'consultation_needed' => 'boolean',
            'consent' => 'boolean',
            'consultation_invitation_sent_at' => 'datetime',
            'consultation_whatsapp_sent_at' => 'datetime',
            'portal_link_sent_at' => 'datetime',
            'portal_whatsapp_sent_at' => 'datetime',
            'portal_onboarded_at' => 'datetime',
        ];
    }
}
