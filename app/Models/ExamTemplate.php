<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamTemplate extends Model
{
    protected $fillable = [
        'class_id',
        'title',
        'description',
        'duration_minutes',
        'result_comment',
    ];

    public function questions()
    {
        return $this->hasMany(ExamQuestion::class)->orderBy('position');
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }
}
