<?php

namespace App\Models;

use App\Models\Classroom;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_name',
        'school_type',
        'class_system',
        'address_line',
        'city',
        'state',
        'contact_person',
        'phone',
        'email',
        'status',
    ];

    public function classes()
    {
        return $this->hasMany(\App\Models\Classroom::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function pricing()
    {
        return $this->hasMany(SchoolPricing::class);
    }

    public function payments()
    {
        return $this->hasMany(SchoolPayment::class);
    }

    public function classTeachers()
    {
        return $this->hasMany(ClassTeacher::class);
    }

    public function classTeacherFeedback()
    {
        return $this->hasMany(ClassTeacherFeedback::class);
    }

    public function timetables()
    {
        return $this->hasMany(SchoolTimetable::class);
    }
}
