<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstructorTimetable extends Model
{
    protected $fillable = [
        'instructor_id',
        'class_id',
        'day_of_week',
        'start_time',
        'end_time',
        'location',
        'notes',
    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }
}
