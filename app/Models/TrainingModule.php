<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingModule extends Model
{
    protected $fillable = [
        'course_id',
        'module_number',
        'title',
        'goal',
        'is_capstone',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_capstone' => 'boolean',
        ];
    }

    public function course()
    {
        return $this->belongsTo(TrainingCourse::class, 'course_id');
    }

    public function topics()
    {
        return $this->hasMany(TrainingTopic::class, 'module_id')->orderBy('sort_order');
    }
}
