<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeScale extends Model
{
    protected $fillable = [
        'min_percentage',
        'max_percentage',
        'letter_grade',
        'created_by',
    ];
}
