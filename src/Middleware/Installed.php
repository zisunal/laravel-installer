<?php

namespace Zisunal\LaravelInstaller\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\HttpFoundation\Response;
use Zisunal\LaravelInstaller\Support\InstallLock;
use Zisunal\LaravelInstaller\Actions\WriteEnv;

class Installed
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle( Request $request, Closure $next ): Response
    {
        try {
            $isInstalled = InstallLock::installed();
        } catch (\Throwable $e) {
            $isInstalled = false;
        }

        if ( function_exists( 'config' ) ) {
            $installPrefix = config( 'installer.route_prefix', 'install' );
            $enabled = config( 'installer.enabled', true );
        } else {
            $installPrefix = env( 'INSTALLER_ROUTE_PREFIX', 'install' );
            $enabled = env( 'INSTALLER_ENABLED', true );
        }
        if (
            ! $request->is( [
                $installPrefix . '*',
                'livewire/*',
                'livewire-*/*',
                'build/*',
                'assets/*',
                'vendor/*',
            ] ) &&
            $enabled &&
            ! $isInstalled
        ) {
            if ( env( 'SESSION_DRIVER' ) === 'database' ) {
                if ( ! config( 'database.default' ) || ! config( 'database.connections.' . config( 'database.default' ) ) ) {
                    WriteEnv::handle( [
                        'SESSION_DRIVER' => 'file',
                        'QUEUE_CONNECTION' => 'sync',
                        'CACHE_STORE' => 'file',
                    ] );
                }
            }
            if ( env( 'APP_KEY', '' ) == '' ) {
                Artisan::call( 'key:generate' );
            }
            return redirect()->route( 'installer.index' );
        }
        
        return $next( $request );
    }
}
