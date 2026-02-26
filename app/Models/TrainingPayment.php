<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingPayment extends Model
{
    protected $fillable = [
        'course_id',
        'user_id',
        'enrollment_id',
        'coupon_id',
        'gateway',
        'reference',
        'amount_kobo',
        'currency',
        'status',
        'gateway_response',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'gateway_response' => 'array',
            'paid_at' => 'datetime',
        ];
    }

    public function course()
    {
        return $this->belongsTo(TrainingCourse::class, 'course_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function enrollment()
    {
        return $this->belongsTo(TrainingEnrollment::class, 'enrollment_id');
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }

    public function invoice()
    {
        return $this->hasOne(TrainingInvoice::class, 'payment_id');
    }
}

