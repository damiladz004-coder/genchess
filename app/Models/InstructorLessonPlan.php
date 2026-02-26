<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstructorLessonPlan extends Model
{
    protected $fillable = [
        'instructor_id',
        'class_id',
        'lesson_date',
        'topic',
        'scheme_reference',
        'objectives',
        'notes',
        'materials_required',
        'resource_text_content',
        'resource_links',
        'resource_files',
        'wippea_warm_up',
        'wippea_introduction',
        'wippea_presentation',
        'wippea_practice',
        'wippea_evaluation',
        'wippea_application',
        'status',
        'review_status',
        'submitted_at',
        'reviewed_by',
        'reviewed_at',
        'review_feedback',
    ];

    protected function casts(): array
    {
        return [
            'lesson_date' => 'date',
            'resource_links' => 'array',
            'resource_files' => 'array',
            'submitted_at' => 'datetime',
            'reviewed_at' => 'datetime',
        ];
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
