<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingCouponRedemption extends Model
{
    protected $fillable = [
        'coupon_id',
        'user_id',
        'enrollment_id',
        'discount_applied_kobo',
    ];

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function enrollment()
    {
        return $this->belongsTo(TrainingEnrollment::class, 'enrollment_id');
    }
}

