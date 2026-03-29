<?php

namespace Zisunal\LaravelInstaller\Livewire\Steps;

use Illuminate\Support\Facades\Artisan;
use Zisunal\LaravelInstaller\Actions\WriteEnv;

class WelcomeStep extends AbstractInstallerStep
{
    public bool $loading;
    public string $dbDriver;
    public string $dbHost;
    public string $dbPort;
    public string $dbName;
    public string $dbUsername;
    public string $dbPassword;
    public ?string $error;
    private array $dbDrivers = [ 'pdo_mysql' => 'mysql', 'pdo_pgsql' => 'pgsql' ];
    public bool $isConnected;

    public function mount(): void
    {
        $this->dbDriver = env( 'DB_CONNECTION', session( 'database' )[ 'DB_CONNECTION' ] ?? 'mysql' );
        $this->dbHost = env( 'DB_HOST', session( 'database' )[ 'DB_HOST' ] ?? 'localhost' );
        $this->dbPort = env( 'DB_PORT', session( 'database' )[ 'DB_PORT' ] ?? '3306' );
        $this->dbName = env( 'DB_DATABASE', session( 'database' )[ 'DB_DATABASE' ] ?? '' );
        $this->dbUsername = env( 'DB_USERNAME', session( 'database' )[ 'DB_USERNAME' ] ?? '' );
        $this->dbPassword = env( 'DB_PASSWORD', session( 'database' )[ 'DB_PASSWORD' ] ?? '' );
        $this->loading = false;
        $this->error = null;
        $this->connected();
    }

    public static function title(): string
    {
        return 'Database';
    }

    public static function description(): ?string
    {
        return 'Configure your database settings.';
    }

    public static function order(): int
    {
        return 1;
    }

    public function startInstallation(): void
    {
        $this->loading = true;
        if ( ! $this->checkVals() ) {
            $this->loading = false;
            return;
        }
        if ( $this->dbDriver === 'mariadb' ) {
            $this->dbDriver = 'mysql';
        }
        session()->put( [ 'database' => [
            ...session( 'database' ) ?? [],
            'DB_CONNECTION' => $this->dbDriver,
            'DB_HOST'       => $this->dbHost,
            'DB_PORT'       => $this->dbPort,
            'DB_DATABASE'   => $this->dbName,
            'DB_USERNAME'   => $this->dbUsername,
            'DB_PASSWORD'   => $this->dbPassword,
        ] ] );
        session()->put( '_installer_config', [
            'generate_app_key' => config( 'installer.generate_app_key', true ),
            'run_migrations' => config( 'installer.run_migrations', true ),
            'run_seeders' => config( 'installer.run_seeders', false ),
            'database_seeder' => config( 'installer.database_seeder', null ),
            'providers_to_register' => config( 'installer.providers_to_register', [] ),
        ] );
        if ( $this->hasNextStep() ) {
            $this->loading = false;
            $this->nextStep();
        }
    }

    private function checkVals(): bool
    {
        if ( ! $this->dbHost || ! $this->dbPort || ! $this->dbName || ! $this->dbUsername ) {
            $this->error = 'Please fill in all required fields.';
            return false;
        }
        foreach ( $this->dbDrivers as $extension => $driver ) {
            if ( $this->dbDriver === $driver && ! extension_loaded( $extension ) ) {
                $this->error = "The required PHP extension for {$driver} is not installed.";
                return false;
            }
        }
        if ( ! $this->isConnected ) {
            $this->error = 'Could not connect to the database with the provided credentials.';
            return false;
        }
        return true;
    }

    public function connected(): bool
    {
        try {
            $dsn = "{$this->dbDriver}:host={$this->dbHost};port={$this->dbPort};dbname={$this->dbName}";
            new \PDO( $dsn, $this->dbUsername, $this->dbPassword );
            $this->isConnected = true;
            return true;
        } catch ( \Exception $e ) {
            $this->isConnected = false;
            return false;
        }
    }

    public function render()
    {
        return view( 'installer::livewire.steps.welcome' );
    }
}
