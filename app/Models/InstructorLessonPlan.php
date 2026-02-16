<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstructorLessonPlan extends Model
{
    protected $fillable = [
        'instructor_id',
        'class_id',
        'lesson_date',
        'topic',
        'scheme_reference',
        'objectives',
        'notes',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'lesson_date' => 'date',
        ];
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }
}
