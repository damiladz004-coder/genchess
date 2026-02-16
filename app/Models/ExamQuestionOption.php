<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamQuestionOption extends Model
{
    protected $fillable = [
        'exam_question_id',
        'option_text',
        'is_correct',
        'position',
    ];

    public function question()
    {
        return $this->belongsTo(ExamQuestion::class, 'exam_question_id');
    }
}
