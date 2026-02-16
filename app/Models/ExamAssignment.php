<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamAssignment extends Model
{
    protected $fillable = [
        'exam_template_id',
        'school_id',
        'class_id',
        'term',
        'session',
        'mode',
        'exam_date',
        'status',
    ];

    public function template()
    {
        return $this->belongsTo(ExamTemplate::class, 'exam_template_id');
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function attempts()
    {
        return $this->hasMany(ExamAttempt::class);
    }
}
