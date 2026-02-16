<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingEnrollment extends Model
{
    protected $fillable = [
        'cohort_id',
        'user_id',
        'status',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'completed_at' => 'datetime',
        ];
    }

    public function cohort()
    {
        return $this->belongsTo(TrainingCohort::class, 'cohort_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function certification()
    {
        return $this->hasOne(Certification::class, 'enrollment_id');
    }
}
