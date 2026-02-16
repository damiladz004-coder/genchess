<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    protected $fillable = [
        'job_posting_id',
        'name',
        'email',
        'phone',
        'cv_path',
        'cover_letter',
        'status',
        'notes',
    ];

    public function job()
    {
        return $this->belongsTo(JobPosting::class, 'job_posting_id');
    }
}
