<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolPricing extends Model
{
    protected $fillable = [
        'school_id',
        'term',
        'session',
        'per_student_amount',
        'currency',
        'notes',
        'active',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
