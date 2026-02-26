<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingTopicQuiz extends Model
{
    protected $fillable = [
        'topic_id',
        'mcq_count',
        'true_false_count',
        'scenario_count',
        'pass_mark',
    ];

    public function topic()
    {
        return $this->belongsTo(TrainingTopic::class, 'topic_id');
    }

    public function questions()
    {
        return $this->hasMany(TrainingTopicQuizQuestion::class, 'quiz_id')->orderBy('sort_order');
    }
}
