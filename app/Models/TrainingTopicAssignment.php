<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingTopicAssignment extends Model
{
    protected $fillable = [
        'topic_id',
        'type',
        'title',
        'instructions',
        'required',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'required' => 'boolean',
        ];
    }

    public function topic()
    {
        return $this->belongsTo(TrainingTopic::class, 'topic_id');
    }

    public function submissions()
    {
        return $this->hasMany(TrainingAssignmentSubmission::class, 'assignment_id');
    }
}
