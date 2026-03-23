<?php

namespace Zisunal\LaravelInstaller\Actions;

use Illuminate\Support\Facades\Artisan;

class RunMigrations
{
    public function handle(): void
    {
        Artisan::call('migrate', ['--force' => true]);
    }
}