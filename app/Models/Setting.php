<?php

namespace App\Models;

use App\Support\MediaPath;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    public function getValueAttribute(?string $value): ?string
    {
        if (!$this->key || !str_ends_with($this->key, '_image')) {
            return $value;
        }

        return MediaPath::toUrl($value);
    }
}
