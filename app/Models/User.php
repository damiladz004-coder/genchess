<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\School;
use App\Models\TrainingEnrollment;
use App\Models\InstructorLessonPlan;
use App\Models\InstructorTimetable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'school_id',
        'phone',
        'certification_level',
        'status',
        'must_change_password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function school()
    {
        return $this->belongsTo(\App\Models\School::class);
    }

    // Role helpers
    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    public function isSchoolAdmin()
    {
        return $this->role === 'school_admin';
    }

    public function isInstructor()
    {
        return $this->role === 'instructor';
    }

    public function isClassTeacher()
    {
        return $this->role === 'class_teacher';
    }

    public function classes()
    {
        return $this->belongsToMany(Classroom::class, 'class_instructor');
    }

    public function teachingClasses()
    {
        return $this->belongsToMany(
            Classroom::class,
            'class_instructor',
            'user_id',
            'class_id'
        );
    }

    public function trainingEnrollments()
    {
        return $this->hasMany(TrainingEnrollment::class, 'user_id');
    }

    public function classTeachers()
    {
        return $this->hasMany(ClassTeacher::class);
    }

    public function lessonPlans()
    {
        return $this->hasMany(InstructorLessonPlan::class, 'instructor_id');
    }

    public function timetables()
    {
        return $this->hasMany(InstructorTimetable::class, 'instructor_id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
