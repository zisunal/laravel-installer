<?php

namespace Zisunal\LaravelInstaller\Support;

use Zisunal\LaravelInstaller\Actions\CreateAdminUser;
use Zisunal\LaravelInstaller\Actions\RunMigrations;
use Zisunal\LaravelInstaller\Actions\WriteEnv;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Bus;
use Zisunal\LaravelInstaller\Actions\WriteConfig;
use Zisunal\LaravelInstaller\Actions\ProviderPublisher;

use function React\Promise\Timer\sleep;

class InstallerManager
{
    public function finalize( array $state ): void
    {
        $environment = $state[ 'environment' ] ?? [];
        $database = $state[ 'database' ] ?? [];
        $admin = $state[ 'admin-user' ] ?? [];
        $providers = $state[ 'providers'] ?? [];
        $configs = $state[ 'config' ] ?? [];
        
        if ( session()->get( '_installer_config.generate_app_key' ) ) {
            Artisan::call( 'key:generate', [ '--force' => true ] );
        }

        $dbDriver = $database[ 'DB_CONNECTION' ] ?? env( 'DB_CONNECTION', 'mysql' );
        $dbHost = $database[ 'DB_HOST' ] ?? env( 'DB_HOST', 'localhost' );
        $dbPort = $database[ 'DB_PORT' ] ?? env( 'DB_PORT', '3306' );
        $dbName = $database[ 'DB_DATABASE' ] ?? env( 'DB_DATABASE', 'laravel' );
        $dbUsername = $database[ 'DB_USERNAME' ] ?? env( 'DB_USERNAME', 'forge' );
        $dbPassword = $database[ 'DB_PASSWORD' ] ?? env( 'DB_PASSWORD', '' );
        config( [ 
            'database.default' => $dbDriver,
            'database.connections.' . $dbDriver => [
                'driver' => $dbDriver,
                'host' => $dbHost,
                'port' => $dbPort,
                'database' => $dbName,
                'username' => $dbUsername,
                'password' => $dbPassword,
            ] 
        ] );

        if ( session()->get( '_installer_config.run_migrations' ) ) {
            app( RunMigrations::class )->handle();
        }

        if ( session()->get( '_installer_config.run_seeders' ) ) {
            $seeder = session()->get( '_installer_config.database_seeder' );

            if (is_string($seeder) && class_exists($seeder)) {
                Artisan::call( 'db:seed', [
                    '--force' => true,
                    '--class' => $seeder,
                ] );
            } else {
                Artisan::call( 'db:seed', [ '--force' => true ] );
            }
        }
        app( CreateAdminUser::class )->handle( $admin );

        Artisan::call( 'config:clear' );
        Artisan::call( 'cache:clear' );
        Artisan::call( 'route:clear' );
        Artisan::call( 'view:clear' );
        Artisan::call( 'optimize:clear' );

        InstallLock::markInstalled();

        $this->modify( $environment, $database, $configs, $providers );
    }

    private function modify( array $environment, array $database, array $configs, array $providers ): void
    {
        if ( ! array_key_exists( 'SESSION_DRIVER', $environment ) ) {
            $environment[ 'SESSION_DRIVER' ] = 'database';
        }
        if ( ! array_key_exists( 'QUEUE_CONNECTION', $environment ) ) {
            $environment[ 'QUEUE_CONNECTION' ] = 'database';
        }
        if ( ! array_key_exists( 'CACHE_STORE', $environment ) ) {
            $environment[ 'CACHE_STORE' ] = 'database';
        }
        sleep( 3.0 )->then( function () use ( $environment, $database, $configs, $providers ) {
            app ( WriteConfig::class )->handle( $configs );
            app( ProviderPublisher::class )->handle( array_merge( $providers, session()->get( '_installer_config.providers_to_register' ) ) );
            app( WriteEnv::class )->handle( array_unique( array_merge( $environment, $database ) ) );
        } );
    }
}