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
        'answers_json',
        'started_at',
        'submitted_at',
        'invitation_sent_at',
    ];

    protected $casts = [
        'answers_json' => 'array',
        'passed' => 'boolean',
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'invitation_sent_at' => 'datetime',
    ];
}
