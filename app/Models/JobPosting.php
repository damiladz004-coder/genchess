<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class JobPosting extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'location',
        'type',
        'description',
        'requirements',
        'active',
    ];

    protected static function booted()
    {
        static::creating(function (self $job) {
            if (!$job->slug) {
                $job->slug = Str::slug($job->title);
            }
        });
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }
}
