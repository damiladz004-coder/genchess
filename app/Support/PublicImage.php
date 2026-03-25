<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PublicImage
{
    public static function store(UploadedFile $file, string $directory = ''): string
    {
        $directory = trim(str_replace('\\', '/', $directory), '/');
        $basePath = public_path('images'.($directory !== '' ? '/'.$directory : ''));

        if (!File::isDirectory($basePath)) {
            File::makeDirectory($basePath, 0755, true);
        }

        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'jpg');
        $filename = now()->format('YmdHis').'_'.Str::lower(Str::random(16)).'.'.$extension;

        $file->move($basePath, $filename);

        return $directory !== '' ? $directory.'/'.$filename : $filename;
    }

    public static function delete(?string $path): void
    {
        $relativePath = self::normalizeRelativePath($path);
        if ($relativePath === null) {
            return;
        }

        $fullPath = public_path('images/'.$relativePath);
        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }
    }

    public static function normalizeRelativePath(?string $path): ?string
    {
        if ($path === null) {
            return null;
        }

        $normalized = trim(str_replace('\\', '/', $path));
        if ($normalized === '') {
            return null;
        }

        $normalized = ltrim($normalized, '/');

        if (str_starts_with($normalized, 'images/')) {
            return substr($normalized, 7);
        }

        if (str_starts_with($normalized, 'storage/')) {
            return substr($normalized, 8);
        }

        return $normalized;
    }
}
