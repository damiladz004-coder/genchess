<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $table = 'classes';

    protected $fillable = [
        'name',
        'level',
        'chess_mode',
        'school_id',
        'status',
    ];

    public function instructors()
    {
        return $this->belongsToMany(
            User::class,
            'class_instructor',
            'class_id',
            'user_id'
        );
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'class_id');
    }

    public function exams()
    {
        return $this->hasMany(Exam::class, 'class_id');
    }

    public function classTeachers()
    {
        return $this->hasMany(ClassTeacher::class, 'class_id');
    }

    public function teacherFeedback()
    {
        return $this->hasMany(ClassTeacherFeedback::class, 'class_id');
    }

    public function lessonPlans()
    {
        return $this->hasMany(InstructorLessonPlan::class, 'class_id');
    }

    public function instructorTimetables()
    {
        return $this->hasMany(InstructorTimetable::class, 'class_id');
    }

    public function schemeItems()
    {
        return $this->hasMany(SchemeOfWorkItem::class, 'class_id');
    }

    public function timetables()
    {
        return $this->hasMany(SchoolTimetable::class, 'class_id');
    }
}
