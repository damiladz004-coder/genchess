<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price_kobo',
        'bulk_price_kobo',
        'sku',
        'stock_quantity',
        'image_placeholder',
        'product_type',
        'featured',
        'allow_quote',
        'has_size_options',
        'has_color_options',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'featured' => 'boolean',
            'allow_quote' => 'boolean',
            'has_size_options' => 'boolean',
            'has_color_options' => 'boolean',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id')->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class, 'product_id')->where('is_primary', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function isOutOfStock(): bool
    {
        return $this->stock_quantity < 1 || $this->status !== 'active';
    }
}

