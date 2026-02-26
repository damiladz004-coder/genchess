<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingEnrollmentTopicProgress extends Model
{
    protected $table = 'training_enrollment_topic_progress';

    protected $fillable = [
        'enrollment_id',
        'topic_id',
        'quiz_score',
        'quiz_passed',
        'quiz_attempts',
        'assignment_status',
        'assignment_submitted_at',
        'assignment_reviewed_at',
        'mentor_approved',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'quiz_score' => 'decimal:2',
            'quiz_passed' => 'boolean',
            'mentor_approved' => 'boolean',
            'assignment_submitted_at' => 'datetime',
            'assignment_reviewed_at' => 'datetime',
            'completed_at' => 'datetime',
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
}
