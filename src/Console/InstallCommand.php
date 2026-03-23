<?php

namespace Zisunal\LaravelInstaller\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class InstallCommand extends Command
{
    protected $signature = 'installer:setup {--force : Force execution}';

    protected $description = 'Run Laravel Installer setup';

    public function handle(): int
    {
        $this->info('Starting installer setup...');

        // Publish configuration
        $this->info('Publishing installer configuration...');
        $this->call('vendor:publish', [
            '--provider' => 'Zisunal\LaravelInstaller\InstallerServiceProvider',
            '--tag' => 'installer-config',
            '--force' => $this->option('force'),
        ]);

        $this->info('Adding InstallerServiceProvider...');
        $providersPath = base_path('bootstrap/providers.php');
        $providersContent = file_get_contents($providersPath);
        $providerClass = 'Zisunal\\LaravelInstaller\\InstallerServiceProvider::class';
        if (! str_contains($providersContent, $providerClass)) {
            $providersContent = str_replace(
                '];',
                "    $providerClass,\n];",
                $providersContent
            );
            file_put_contents($providersPath, $providersContent);
            $this->info('Added InstallerServiceProvider to providers.php');
        } else {
            $this->info('InstallerServiceProvider already exists in providers.php');
        }

        if (empty(config('app.key')) && config('installer.generate_app_key', true)) {
            $this->info('Generating application key...');
            Artisan::call('key:generate', ['--force' => true]);
            $this->line(Artisan::output());
        } else {
            $this->info('Application key already exists.');
        }

        if (! file_exists(public_path('storage')) && config('installer.storage_link', true)) {
            $this->info('Creating storage link...');
            Artisan::call('storage:link');
        }

        $this->info('Installer setup completed successfully.');

        return self::SUCCESS;
    }
}