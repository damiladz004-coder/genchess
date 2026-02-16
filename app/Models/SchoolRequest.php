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
        'consent',
        'status',
        'school_id',
    ];
}
