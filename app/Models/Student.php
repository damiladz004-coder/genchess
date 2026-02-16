<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'gender',
        'school_id',
        'class_id',
        'admission_number',
        'status',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function class()
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }

    public function examResults()
    {
        return $this->hasMany(ExamResult::class);
    }

    public function examAttempts()
    {
        return $this->hasMany(ExamAttempt::class);
    }
}
