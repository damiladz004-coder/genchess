<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingReferralReward extends Model
{
    protected $fillable = [
        'user_id',
        'coupon_id',
        'qualified_paid_referrals',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }
}

