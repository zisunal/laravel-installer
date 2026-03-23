<?php

namespace Zisunal\LaravelInstaller\Actions;

use Winter\LaravelConfigWriter\ArrayFile;

class WriteConfig
{
    public static function handle( array $pairs ): void
    {
        foreach ( $pairs as $fileName => $confs ) {
            $conf = ArrayFile::open( config_path( $fileName . '.php' ) );
            $conf->set( $confs );
            $conf->write();
        }
    }
}