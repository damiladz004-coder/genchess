<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = [
        'school_id',
        'class_id',
        'title',
        'term',
        'session',
        'exam_date',
        'total_marks',
    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }

    public function results()
    {
        return $this->hasMany(ExamResult::class);
    }

}
