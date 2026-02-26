<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BulkOrder extends Model
{
    protected $fillable = [
        'user_id',
        'organization_name',
        'contact_person',
        'phone',
        'email',
        'delivery_location',
        'items_json',
        'additional_notes',
        'status',
        'custom_price_kobo',
        'invoice_number',
        'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'items_json' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

