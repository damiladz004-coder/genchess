<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class MediaPath
{
    public static function toUrl(?string $path): ?string
    {
        if ($path === null) {
            return null;
        }

        $path = trim(str_replace('\\', '/', $path));

        if ($path === '') {
            return null;
        }

        if (
            str_starts_with($path, 'http://') ||
            str_starts_with($path, 'https://') ||
            str_starts_with($path, '//') ||
            str_starts_with($path, 'data:')
        ) {
            return $path;
        }

        $relativePath = ltrim($path, '/');

        if (str_starts_with($relativePath, 'storage/')) {
            return asset($relativePath);
        }

        if (Storage::disk('public')->exists($relativePath)) {
            return Storage::url($relativePath);
        }

        if (str_starts_with($relativePath, 'public/')) {
            $publicDiskPath = substr($relativePath, 7);

            if ($publicDiskPath !== '' && Storage::disk('public')->exists($publicDiskPath)) {
                return Storage::url($publicDiskPath);
            }
        }

        if (file_exists(public_path($relativePath))) {
            return asset($relativePath);
        }

        return str_starts_with($path, '/') ? $path : asset($relativePath);
    }
}
