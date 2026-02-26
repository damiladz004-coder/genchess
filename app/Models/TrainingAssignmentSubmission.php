<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingAssignmentSubmission extends Model
{
    protected $fillable = [
        'enrollment_id',
        'topic_id',
        'assignment_id',
        'submission_text',
        'submission_url',
        'status',
        'mentor_feedback',
        'submitted_at',
        'reviewed_at',
        'reviewed_by',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'reviewed_at' => 'datetime',
        ];
    }

    public function enrollment()
    {
        return $this->belongsTo(TrainingEnrollment::class, 'enrollment_id');
    }

    public function topic()
    {
        return $this->belongsTo(TrainingTopic::class, 'topic_id');
    }

    public function assignment()
    {
        return $this->belongsTo(TrainingTopicAssignment::class, 'assignment_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
