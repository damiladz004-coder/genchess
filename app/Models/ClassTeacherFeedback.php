<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassTeacherFeedback extends Model
{
    protected $table = 'class_teacher_feedback';

    protected $fillable = [
        'school_id',
        'class_id',
        'class_teacher_id',
        'instructor_id',
        'rating',
        'term',
        'academic_year',
        'comments',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }

    public function classTeacher()
    {
        return $this->belongsTo(ClassTeacher::class);
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }
}
