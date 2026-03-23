<?php

namespace Zisunal\LaravelInstaller\Actions;

use Illuminate\Support\Facades\File;

class ProviderPublisher
{
    public static function handle( array $providers ): void
    {
        $providerPath = base_path( 'bootstrap/providers.php' );
        if ( File::exists( $providerPath ) ) {
            $providersToAdd = array_diff( $providers, require $providerPath );
            foreach ( $providersToAdd as $provider ) {
                if ( ! class_exists( $provider ) ) {
                    unset( $providersToAdd[ array_search( $provider, $providersToAdd ) ] );
                } else {
                    $providersToAdd[ array_search( $provider, $providersToAdd ) ] .= "::class";
                }
            }

            if ( count( $providersToAdd ) > 0 ) {
                $existingProviders = File::get( $providerPath );
                $providersString = implode( ",\n    ", $providersToAdd ) . ',';
                $newProviders = str_replace( "];\n", "    $providersString\n];\n", $existingProviders );
                File::put( $providerPath, $newProviders );
            }
        } else {
            foreach ( $providers as $key => $provider ) {
                if ( ! class_exists( $provider ) ) {
                    unset( $providers[ $key ] );
                } else {
                    $providers[ $key ] .= "::class";
                }
            }
            $providersString = implode( ",\n    ", $providers ) . ',';
            $content = "<?php\n\nreturn [\n    $providersString\n];\n";
            File::put( $providerPath, $content );
        }
    }

}