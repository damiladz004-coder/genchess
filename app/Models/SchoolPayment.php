<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolPayment extends Model
{
    protected $fillable = [
        'school_id',
        'term',
        'session',
        'term_start_date',
        'term_end_date',
        'first_due_date',
        'second_due_date',
        'first_amount',
        'second_amount',
        'student_count',
        'per_student_amount',
        'total_due',
        'amount_paid',
        'status',
        'due_date',
        'paid_at',
        'reference',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'term_start_date' => 'date',
            'term_end_date' => 'date',
            'first_due_date' => 'date',
            'second_due_date' => 'date',
            'paid_at' => 'datetime',
        ];
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
