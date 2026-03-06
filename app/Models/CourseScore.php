<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseScore extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'total_score',
    ];

    protected function casts(): array
    {
        return [
            'total_score' => 'decimal:2',
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
}

