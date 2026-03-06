<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentResultAudit extends Model
{
    protected $fillable = [
        'student_result_id',
        'action',
        'changed_by',
        'before_values',
        'after_values',
        'changed_at',
    ];

    protected function casts(): array
    {
        return [
            'before_values' => 'array',
            'after_values' => 'array',
            'changed_at' => 'datetime',
        ];
    }

    public function studentResult()
    {
        return $this->belongsTo(StudentResult::class);
    }
}
