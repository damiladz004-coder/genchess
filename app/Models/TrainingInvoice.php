<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingInvoice extends Model
{
    protected $fillable = [
        'payment_id',
        'invoice_number',
        'subtotal_kobo',
        'discount_kobo',
        'total_kobo',
        'currency',
        'issued_at',
    ];

    protected function casts(): array
    {
        return [
            'issued_at' => 'datetime',
        ];
    }

    public function payment()
    {
        return $this->belongsTo(TrainingPayment::class, 'payment_id');
    }
}

