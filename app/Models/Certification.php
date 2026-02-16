<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    protected $fillable = [
        'enrollment_id',
        'certificate_code',
        'issued_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'issued_at' => 'datetime',
        ];
    }

    public function enrollment()
    {
        return $this->belongsTo(TrainingEnrollment::class, 'enrollment_id');
    }
}
