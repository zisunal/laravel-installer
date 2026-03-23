<?php

namespace Zisunal\LaravelInstaller;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Zisunal\LaravelInstaller\Support\StepRegistry;
use Zisunal\LaravelInstaller\Middleware\Installed;

class InstallerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/installer.php', 'installer');

        $this->app->singleton(StepRegistry::class, fn () => new StepRegistry());
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/installer.php' => config_path('installer.php'),
        ], 'installer-config');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'installer');

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        $this->registerLivewireComponents();

        if ($this->app->runningInConsole()) {
            $this->commands([
                \Zisunal\LaravelInstaller\Console\InstallCommand::class,
            ]);
        }

        $this->app[ 'router' ]->aliasMiddleware( 'installed', Installed::class );
    }

    protected function registerLivewireComponents(): void
    {
        Livewire::component('installer-wizard', \Zisunal\LaravelInstaller\Livewire\InstallerWizard::class);

        foreach (app(StepRegistry::class)->all() as $stepClass) {
            Livewire::component($this->aliasFor($stepClass), $stepClass);
        }
    }

    protected function aliasFor(string $class): string
    {
        $base = class_basename($class);

        return strtolower(
            preg_replace('/(?<!^)[A-Z]/', '-$0', str_replace('Step', '', $base))
        );
    }
}