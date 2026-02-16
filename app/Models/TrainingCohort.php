<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingCohort extends Model
{
    protected $fillable = [
        'course_id',
        'name',
        'start_date',
        'end_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function course()
    {
        return $this->belongsTo(TrainingCourse::class, 'course_id');
    }

    public function enrollments()
    {
        return $this->hasMany(TrainingEnrollment::class, 'cohort_id');
    }
}
