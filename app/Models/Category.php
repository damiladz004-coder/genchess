<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'image',
        'status',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}

