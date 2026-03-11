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
        'score',
        'total_questions',
        'percentage',
        'passed',
        'stage_two_status',
        'stage_two_notes',
        'stage_two_interviewed_at',
        'stage_three_status',
        'stage_three_notes',
        'stage_three_interviewed_at',
        'final_status',
        'approved_at',
        'rejected_at',
        'training_recommended_at',
        'certified_at',
        'user_id',
        'onboarded_at',
        'answers_json',
        'started_at',
        'submitted_at',
        'invitation_sent_at',
    ];

    protected $casts = [
        'answers_json' => 'array',
        'passed' => 'boolean',
        'stage_two_interviewed_at' => 'datetime',
        'stage_three_interviewed_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'training_recommended_at' => 'datetime',
        'certified_at' => 'datetime',
        'onboarded_at' => 'datetime',
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
