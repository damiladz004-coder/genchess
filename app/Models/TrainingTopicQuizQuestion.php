<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingTopicQuizQuestion extends Model
{
    protected $fillable = [
        'quiz_id',
        'type',
        'question',
        'options',
        'correct_answer',
        'explanation',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'options' => 'array',
        ];
    }

    public function quiz()
    {
        return $this->belongsTo(TrainingTopicQuiz::class, 'quiz_id');
    }
}
