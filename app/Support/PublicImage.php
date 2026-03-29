<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PublicImage
{
    public const BASE_DIRECTORY = 'images';
    public const PRODUCTS_DIRECTORY = 'products';
    public const SCHOOLS_DIRECTORY = 'schools';
    public const SETTINGS_DIRECTORY = 'settings';

    public static function store(UploadedFile $file, string $directory = ''): string
    {
        $directory = self::normalizeDirectory($directory);
        $basePath = public_path(self::BASE_DIRECTORY.($directory !== '' ? '/'.$directory : ''));

        if (!File::isDirectory($basePath)) {
            File::makeDirectory($basePath, 0755, true);
        }

        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'jpg');
        $filename = now()->format('YmdHis').'_'.Str::lower(Str::random(16)).'.'.$extension;

        $file->move($basePath, $filename);

        return $directory !== '' ? $directory.'/'.$filename : $filename;
    }

    public static function url(?string $path, ?string $fallback = null): ?string
    {
        if ($path === null) {
            return self::fallbackUrl($fallback);
        }

        $path = trim(str_replace('\\', '/', $path));

        if ($path === '') {
            return self::fallbackUrl($fallback);
        }

        if (
            str_starts_with($path, 'http://') ||
            str_starts_with($path, 'https://') ||
            str_starts_with($path, '//') ||
            str_starts_with($path, 'data:')
        ) {
            return $path;
        }

        $relativePath = self::normalizeRelativePath($path);
        if ($relativePath && self::exists($relativePath)) {
            return asset(self::BASE_DIRECTORY.'/'.$relativePath);
        }

        $rawRelativePath = ltrim($path, '/');
        if ($rawRelativePath !== '' && File::exists(public_path($rawRelativePath))) {
            return asset($rawRelativePath);
        }

        return self::fallbackUrl($fallback);
    }

    public static function exists(?string $path): bool
    {
        $relativePath = self::normalizeRelativePath($path);

        return $relativePath !== null && File::exists(self::absolutePath($relativePath));
    }

    public static function absolutePath(?string $path): ?string
    {
        $relativePath = self::normalizeRelativePath($path);

        return $relativePath === null
            ? null
            : public_path(self::BASE_DIRECTORY.'/'.$relativePath);
    }

    public static function debug(?string $path, ?string $fallback = null): array
    {
        $relativePath = self::normalizeRelativePath($path);

        return [
            'database_path' => $relativePath,
            'public_path' => self::absolutePath($relativePath),
            'file_exists' => self::exists($relativePath),
            'asset_url' => self::url($relativePath, $fallback),
        ];
    }

    public static function delete(?string $path): void
    {
        $relativePath = self::normalizeRelativePath($path);
        if ($relativePath === null) {
            return;
        }

        $fullPath = self::absolutePath($relativePath);
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

        $segments = array_values(array_filter(
            explode('/', $normalized),
            fn (string $segment) => $segment !== '' && $segment !== '.' && $segment !== '..'
        ));

        return $segments === [] ? null : implode('/', $segments);
    }

    private static function normalizeDirectory(string $directory): string
    {
        return self::normalizeRelativePath($directory) ?? '';
    }

    private static function fallbackUrl(?string $fallback): ?string
    {
        if ($fallback === null || $fallback === '') {
            return null;
        }

        if (
            str_starts_with($fallback, 'http://') ||
            str_starts_with($fallback, 'https://') ||
            str_starts_with($fallback, '//') ||
            str_starts_with($fallback, 'data:')
        ) {
            return $fallback;
        }

        return asset(ltrim($fallback, '/'));
    }
}
