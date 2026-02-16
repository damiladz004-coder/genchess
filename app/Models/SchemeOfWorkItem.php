<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchemeOfWorkItem extends Model
{
    protected $fillable = [
        'class_id',
        'term',
        'week_number',
        'topic',
        'objectives',
    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }
}
