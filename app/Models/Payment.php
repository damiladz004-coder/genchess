<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public const PURPOSE_TRAINING = 'training';
    public const PURPOSE_STORE = 'store';
    public const PURPOSE_SCHOOL = 'school';
    public const PURPOSE_CONSULTATION = 'consultation';
    public const PURPOSE_TOURNAMENT = 'tournament';

    protected $fillable = [
        'user_id',
        'email',
        'reference',
        'amount',
        'purpose',
        'status',
        'metadata',
        'gateway_response',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'gateway_response' => 'array',
            'paid_at' => 'datetime',
        ];
    }

    public static function purposes(): array
    {
        return [
            self::PURPOSE_TRAINING,
            self::PURPOSE_STORE,
            self::PURPOSE_SCHOOL,
            self::PURPOSE_CONSULTATION,
            self::PURPOSE_TOURNAMENT,
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
