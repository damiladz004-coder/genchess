<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamTemplate extends Model
{
    protected $fillable = [
        'title',
        'description',
        'duration_minutes',
    ];

    public function questions()
    {
        return $this->hasMany(ExamQuestion::class)->orderBy('position');
    }
}
