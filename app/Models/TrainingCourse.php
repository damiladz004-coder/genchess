<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingCourse extends Model
{
    protected $fillable = [
        'title',
        'description',
        'duration_weeks',
        'active',
    ];

    public function cohorts()
    {
        return $this->hasMany(TrainingCohort::class, 'course_id');
    }
}
