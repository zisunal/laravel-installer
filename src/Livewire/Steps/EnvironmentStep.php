<?php

namespace Zisunal\LaravelInstaller\Livewire\Steps;

class EnvironmentStep extends AbstractInstallerStep
{
    public string $app_name = '';

    public string $app_url = '';

    public string $app_env = '';

    public string $app_locale = '';

    public string $app_timezone = '';

    public function mount(): void
    {
        $this->app_name = env( 'APP_NAME', 'Laravel' );
        $this->app_url = url( '/' ) ?? env( 'APP_URL', url( '/' ) );
        $this->app_env = env( 'APP_ENV', 'production' );
        $this->app_locale = env( 'APP_LOCALE', 'en' );
        $this->app_timezone = config( 'app.timezone', 'UTC' );
    }

    public static function title(): string
    {
        return 'Environment';
    }

    public static function description(): ?string
    {
        return 'Set app details.';
    }

    public static function order(): int
    {
        return 3;
    }

    protected function rules(): array
    {
        return [
            'app_name' => ['required', 'string', 'max:255'],
            'app_url' => ['required', 'url', 'max:255'],
            'app_env' => ['required', 'string', 'max:50'],
            'app_locale' => ['required', 'string', 'max:20'],
            'app_timezone' => ['required', 'string', 'max:50'],
        ];
    }

    public function enSetup(): void
    {
        $this->validate();

        session()->put( 'environment', [
            ...session( 'environment' ) ?? [],
            'APP_NAME' => $this->app_name,
            'APP_URL' => $this->app_url,
            'APP_ENV' => $this->app_env,
            'APP_LOCALE' => $this->app_locale,
            'APP_DEBUG' => $this->app_env === 'production' || $this->app_env === 'staging' ? 'false' : 'true',
        ] );
        session()->put( 'config', [
            ...session( 'config' ) ?? [],    
            'app' => [
                'timezone' => $this->app_timezone,
            ],
        ] );

        $this->nextStep();
    }

    public function render()
    {
        return view( 'installer::livewire.steps.environment' );
    }
}