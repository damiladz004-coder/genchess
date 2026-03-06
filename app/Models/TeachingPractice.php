<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeachingPractice extends Model
{
    protected $table = 'teaching_practice';

    protected $fillable = [
        'user_id',
        'course_id',
        'lesson_topic',
        'video_url',
        'description',
        'score',
        'instructor_feedback',
        'reviewed_by',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'decimal:2',
            'reviewed_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function course()
    {
        return $this->belongsTo(TrainingCourse::class, 'course_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}

