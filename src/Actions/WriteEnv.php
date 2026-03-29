<?php

namespace Zisunal\LaravelInstaller\Actions;

use Winter\LaravelConfigWriter\EnvFile;

class WriteEnv
{
    public static function handle( array $pairs ): void
    {
        // Add double quotes to values with spaces, otherwise the .env file will be broken
//        foreach ( $pairs as $key => $value ) {
//            if ( str_contains( $value, ' ' ) ) {
//                $pairs[ $key ] = '"' . $value . '"';
//            }
//        }
        $envPath = base_path( '.env' );

        $envs = EnvFile::open( $envPath );
        $envs->set( $pairs );
        $envs->write();
    }
}
