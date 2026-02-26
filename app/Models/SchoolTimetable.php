<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolTimetable extends Model
{
    protected $fillable = [
        'school_id',
        'class_id',
        'day_of_week',
        'start_time',
        'end_time',
        'location',
        'notes',
        'status',
        'submitted_at',
        'reviewed_at',
        'review_comment',
        'instructor_review_status',
        'instructor_review_comment',
        'instructor_reviewed_at',
        'instructor_reviewed_by',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'instructor_reviewed_at' => 'datetime',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }

    public function instructorReviewer()
    {
        return $this->belongsTo(User::class, 'instructor_reviewed_by');
    }
}
