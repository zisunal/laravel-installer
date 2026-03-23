<?php

namespace Zisunal\LaravelInstaller\Actions;

use Winter\LaravelConfigWriter\EnvFile;

class WriteEnv
{
    public static function handle( array $pairs ): void
    {
        $envPath = base_path( '.env' );

        $envs = EnvFile::open( $envPath );
        $envs->set( $pairs );
        $envs->write();
    }
}