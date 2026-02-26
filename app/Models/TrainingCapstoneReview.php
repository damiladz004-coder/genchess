<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingCapstoneReview extends Model
{
    protected $fillable = [
        'enrollment_id',
        'video_url',
        'status',
        'mentor_feedback',
        'reviewed_by',
        'reviewed_at',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
            'approved_at' => 'datetime',
        ];
    }

    public function enrollment()
    {
        return $this->belongsTo(TrainingEnrollment::class, 'enrollment_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
