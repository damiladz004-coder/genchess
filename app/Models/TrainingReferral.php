<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingReferral extends Model
{
    protected $fillable = [
        'referrer_id',
        'referred_user_id',
        'payment_status',
        'reward_issued',
    ];

    protected function casts(): array
    {
        return [
            'reward_issued' => 'boolean',
        ];
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    public function referredUser()
    {
        return $this->belongsTo(User::class, 'referred_user_id');
    }
}

