<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'type',
        'discount_type',
        'discount_value',
        'usage_limit',
        'used_count',
        'expiry_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'expiry_date' => 'datetime',
        ];
    }

    public function redemptions()
    {
        return $this->hasMany(TrainingCouponRedemption::class, 'coupon_id');
    }
}

