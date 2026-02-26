<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamQuestion extends Model
{
    protected $fillable = [
        'exam_template_id',
        'question_text',
        'question_image_path',
        'marks',
        'position',
    ];

    public function template()
    {
        return $this->belongsTo(ExamTemplate::class, 'exam_template_id');
    }

    public function options()
    {
        return $this->hasMany(ExamQuestionOption::class)->orderBy('position');
    }
}
