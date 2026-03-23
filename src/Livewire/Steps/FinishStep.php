<?php

namespace Zisunal\LaravelInstaller\Livewire\Steps;

use Zisunal\LaravelInstaller\Support\InstallerManager;

class FinishStep extends AbstractInstallerStep
{
    public static function title(): string
    {
        return 'Finish';
    }

    public static function description(): ?string
    {
        return 'Review and complete installation.';
    }

    public static function order(): int
    {
        return 99;
    }

    public function finish(): mixed
    {
        app( InstallerManager::class )->finalize( $this->getSummary() );
        return redirect()->to( config( 'installer.redirect_after_install', '/admin' ) );
    }

    public function render()
    {
        return view( 'installer::livewire.steps.finish', [
            'summary' => $this->getSummary( true ),
        ] );
    }

    private function getSummary( bool $toPreview = false, array $valuesToHide = [ 'password', 'token' ] ): array
    {
        $valuesToHide = $toPreview ? array_map( 'strtolower', $valuesToHide ) : [];

        return collect( session()->all() )
            ->reject( fn ( $value, $key ) => str_starts_with( $key, '_' ) )
            ->map( function ( $value, $key ) use ( $valuesToHide ) {
                if ( is_array( $value ) ) {
                    return collect( $value )
                        ->map( fn ( $subValue, $subKey ) => $this->maskIfNeeded( $subValue, $subKey, $valuesToHide ) )
                        ->all();
                }

                return $this->maskIfNeeded( $value, $key, $valuesToHide );
            })
            ->all();
    }

    private function maskIfNeeded( mixed $value, string $key, array $valuesToHide ): mixed
    {
        if ( ! is_string( $value ) ) {
            return $value;
        }

        foreach ( $valuesToHide as $hiddenKey ) {
            if ( str_contains( strtolower( $key ), $hiddenKey ) ) {
                return str_repeat( '*', strlen( $value ) );
            }
        }

        return $value;
    }
}