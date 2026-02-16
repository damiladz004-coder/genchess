<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassTeacher extends Model
{
    protected $fillable = [
        'user_id',
        'school_id',
        'class_id',
        'name',
        'email',
        'phone',
        'status',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
