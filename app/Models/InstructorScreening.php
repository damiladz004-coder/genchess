<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstructorScreening extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'location',
        'interview_mode',
        'preferred_interview_date',
        'preferred_interview_time',
        'preferred_interview_notes',
        'score',
        'total_questions',
        'percentage',
        'passed',
        'stage_two_status',
        'stage_two_notes',
        'stage_two_interviewed_at',
        'stage_two_meeting_type',
        'stage_two_meeting_link',
        'stage_two_meeting_id',
        'stage_two_passcode',
        'stage_two_meeting_date',
        'stage_two_meeting_time',
        'stage_two_invitation_sent_at',
        'stage_two_whatsapp_sent_at',
        'stage_three_status',
        'stage_three_notes',
        'stage_three_interviewed_at',
        'stage_three_meeting_type',
        'stage_three_meeting_link',
        'stage_three_meeting_id',
        'stage_three_passcode',
        'stage_three_meeting_date',
        'stage_three_meeting_time',
        'stage_three_invitation_sent_at',
        'stage_three_whatsapp_sent_at',
        'final_status',
        'approved_at',
        'rejected_at',
        'training_recommended_at',
        'certified_at',
        'user_id',
        'onboarded_at',
        'onboarding_link_sent_at',
        'onboarding_whatsapp_sent_at',
        'answers_json',
        'started_at',
        'submitted_at',
        'invitation_sent_at',
    ];

    protected $casts = [
        'answers_json' => 'array',
        'passed' => 'boolean',
        'preferred_interview_date' => 'date',
        'preferred_interview_time' => 'datetime:H:i',
        'stage_two_interviewed_at' => 'datetime',
        'stage_two_meeting_date' => 'date',
        'stage_two_meeting_time' => 'datetime:H:i',
        'stage_two_invitation_sent_at' => 'datetime',
        'stage_two_whatsapp_sent_at' => 'datetime',
        'stage_three_interviewed_at' => 'datetime',
        'stage_three_meeting_date' => 'date',
        'stage_three_meeting_time' => 'datetime:H:i',
        'stage_three_invitation_sent_at' => 'datetime',
        'stage_three_whatsapp_sent_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'training_recommended_at' => 'datetime',
        'certified_at' => 'datetime',
        'onboarded_at' => 'datetime',
        'onboarding_link_sent_at' => 'datetime',
        'onboarding_whatsapp_sent_at' => 'datetime',
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'invitation_sent_at' => 'datetime',
    ];

    public function instructorProfile()
    {
        return $this->hasOne(InstructorProfile::class, 'screening_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
