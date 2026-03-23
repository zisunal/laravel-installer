<?php

use Illuminate\Support\Facades\Route;
use Zisunal\LaravelInstaller\Support\InstallLock;

Route::middleware( config( 'installer.middleware', [] ) )
    ->prefix( config( 'installer.route_prefix', 'install' ) )
    ->name( 'installer.' )
    ->group(function () {
        Route::get( '/', function () {
            abort_if( ! config( 'installer.enabled', true ), 404 );
            abort_if( InstallLock::installed(), 404 );

            return view( 'installer::page' );
        } )->name( 'index' );
    });