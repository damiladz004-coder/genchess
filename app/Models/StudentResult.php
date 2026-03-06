<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentResult extends Model
{
    protected $fillable = [
        'student_id',
        'school_id',
        'class_id',
        'term',
        'academic_session',
        'test_score',
        'test_max',
        'practical_score',
        'practical_max',
        'exam_score',
        'exam_max',
        'exam_mode',
        'final_percentage',
        'grade',
        'instructor_comment',
        'system_feedback',
        'graded_by',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
        ];
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }

    public function grader()
    {
        return $this->belongsTo(User::class, 'graded_by');
    }

    public function audits()
    {
        return $this->hasMany(StudentResultAudit::class);
    }
}
