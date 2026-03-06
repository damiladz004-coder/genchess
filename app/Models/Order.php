<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',
        'customer_name',
        'phone',
        'email',
        'delivery_address',
        'state',
        'order_type',
        'payment_method',
        'payment_status',
        'status',
        'subtotal_kobo',
        'delivery_fee_kobo',
        'delivery_fee',
        'total_kobo',
        'currency',
        'notes',
        'reference',
        'paystack_response',
        'paid_at',
        'address_verified_at',
        'stock_confirmed_at',
        'packed_at',
        'quality_checked_at',
        'courier_name',
        'tracking_number',
        'shipped_at',
        'delivered_at',
        'delivery_confirmed_at',
        'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'paystack_response' => 'array',
            'paid_at' => 'datetime',
            'address_verified_at' => 'datetime',
            'stock_confirmed_at' => 'datetime',
            'packed_at' => 'datetime',
            'quality_checked_at' => 'datetime',
            'shipped_at' => 'datetime',
            'delivered_at' => 'datetime',
            'delivery_confirmed_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
}
