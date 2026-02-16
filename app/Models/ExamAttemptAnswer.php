<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamAttemptAnswer extends Model
{
    protected $fillable = [
        'exam_attempt_id',
        'exam_question_id',
        'exam_question_option_id',
        'is_correct',
        'marks_awarded',
    ];

    public function attempt()
    {
        return $this->belongsTo(ExamAttempt::class, 'exam_attempt_id');
    }

    public function question()
    {
        return $this->belongsTo(ExamQuestion::class, 'exam_question_id');
    }

    public function option()
    {
        return $this->belongsTo(ExamQuestionOption::class, 'exam_question_option_id');
    }
}
