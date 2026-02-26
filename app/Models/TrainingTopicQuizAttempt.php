<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingTopicQuizAttempt extends Model
{
    protected $fillable = [
        'enrollment_id',
        'topic_id',
        'quiz_id',
        'answers_json',
        'total_questions',
        'correct_answers',
        'score',
        'passed',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'answers_json' => 'array',
            'score' => 'decimal:2',
            'passed' => 'boolean',
            'submitted_at' => 'datetime',
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

    public function quiz()
    {
        return $this->belongsTo(TrainingTopicQuiz::class, 'quiz_id');
    }
}
