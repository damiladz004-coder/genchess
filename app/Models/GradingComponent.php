<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradingComponent extends Model
{
    protected $fillable = [
        'name',
        'weight_percentage',
        'created_by',
        'school_id',
    ];
}
