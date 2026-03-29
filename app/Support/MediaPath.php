<?php

namespace App\Support;

class MediaPath
{
    public static function toUrl(?string $path, ?string $fallback = null): ?string
    {
        return PublicImage::url($path, $fallback);
    }
}
