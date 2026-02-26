<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryLog extends Model
{
    protected $fillable = [
        'product_id',
        'order_id',
        'created_by',
        'action',
        'quantity',
        'before_stock',
        'after_stock',
        'note',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function actor()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

