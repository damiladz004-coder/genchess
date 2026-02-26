<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingTopic extends Model
{
    protected $fillable = [
        'module_id',
        'topic_number',
        'title',
        'duration_minutes',
        'level',
        'objectives',
        'video_structure',
        'lesson_notes',
        'quiz_focus',
        'assessment',
        'practical_assignment',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'objectives' => 'array',
            'video_structure' => 'array',
            'lesson_notes' => 'array',
            'quiz_focus' => 'array',
            'assessment' => 'array',
            'practical_assignment' => 'array',
        ];
    }

    public function module()
    {
        return $this->belongsTo(TrainingModule::class, 'module_id');
    }

    public function quiz()
    {
        return $this->hasOne(TrainingTopicQuiz::class, 'topic_id');
    }

    public function assignments()
    {
        return $this->hasMany(TrainingTopicAssignment::class, 'topic_id')->orderBy('sort_order');
    }

    public function progresses()
    {
        return $this->hasMany(TrainingEnrollmentTopicProgress::class, 'topic_id');
    }

    public function quizAttempts()
    {
        return $this->hasMany(TrainingTopicQuizAttempt::class, 'topic_id');
    }
}
