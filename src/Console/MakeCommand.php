<?php

namespace Zisunal\LaravelInstaller\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Binafy\LaravelStub\Facades\LaravelStub;
use function Laravel\Prompts\text, Laravel\Prompts\select, Laravel\Prompts\textarea, Laravel\Prompts\number;

class MakeCommand extends Command
{
    protected $signature = 'make:installer-step {--force : Force execution}';

    protected $description = 'Create a new installer step.';

    public function handle(): int
    {
        $this->info('Creating installer step...');
        $title = text( label: 'Installer step title', placeholder: 'e.g. Redis Setup' );
        $className = Str::studly( Str::singular( $title ) ) . 'Step';
        $viewName = Str::kebab( Str::singular( $title ) );
        $description = textarea( label: 'Installer step description', placeholder: 'e.g. Configure Redis connection settings.' );
        $order = number( 
            label: 'Installer step order', 
            placeholder: 'e.g. 5', 
            min: 1,
            step: 1,
            validate: function ( $value ) {
                if ( $value < 1 ) {
                    return 'Order must be at least 1.';
                }
                if ( $value > 99 ) {
                    return 'Order must be less than 100.';
                }
                return true;
            },
            default: $this->getNextOrder()
        );
        $discoverable = config( 'installer.discover.enabled', true );
        $pathsWithNamespacesArray = config( 'installer.discover.paths', [] );
        if ( $discoverable && count( $pathsWithNamespacesArray ) > 0 ) {
            $path = select(
                label: 'Where would you like to create the installer step?',
                options: collect( $pathsWithNamespacesArray )->mapWithKeys( function ( $namespace, $path ) {
                    return [ $namespace => $path ];
                } )->toArray()
            );
            $namespace = collect( $pathsWithNamespacesArray )->get( $path );
            $this->info( 'This is an auto-discoverable installer step.' );
        } else {
            $path = app_path( 'Installer/Steps/' );
            $namespace = app()->getNamespace() . 'Installer\\Steps';
            $this->info( 'This installer step will not be auto-discoverable. You will need to add it to the installer configuration.' );
        }
        LaravelStub::from( __DIR__ . '/boilerplates/Step.zisunal' )
            ->to( $path )
            ->name( $className )
            ->ext( 'php' )
            ->replaces( [
                'NAME_SPACE' => $namespace,
                'CLASS_NAME' => $className,
                'TITLE' => $title,
                'DESCRIPTION' => $description,
                'ORDER' => $order,
                'VIEW_NAME' => $viewName,
            ] )
            ->generate();
        LaravelStub::from( __DIR__ . '/boilerplates/View.zisunal' )
            ->to( resource_path( 'views/installer/steps/' ) )
            ->name( $viewName )
            ->ext( 'blade.php' )
            ->generate();

        $this->info('Installer step created successfully.');

        return self::SUCCESS;
    }

    private function getNextOrder(): int
    {
        $steps = count( config( 'installer.steps', [] ) );
        $discoverable = config( 'installer.discover.enabled', true );
        if ( $discoverable ) {
            $steps += count( config( 'installer.discover.paths', [] ) );
        }
        return $steps;
    }
}