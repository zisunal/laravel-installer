<?php

namespace Zisunal\LaravelInstaller\Support;

use Illuminate\Support\Facades\File;

class InstallLock
{
    public static function path(): string
    {
        return config('installer.lock_file', storage_path('app/installed.lock'));
    }

    public static function installed(): bool
    {
        return File::exists(static::path());
    }

    public static function markInstalled(): void
    {
        File::ensureDirectoryExists(dirname(static::path()));
        File::put(static::path(), now()->toDateTimeString());
    }

    public static function clear(): void
    {
        if (File::exists(static::path())) {
            File::delete(static::path());
        }
    }
}