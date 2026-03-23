<?php

namespace Zisunal\LaravelInstaller\Livewire;

use Livewire\Component;
use Spatie\LivewireWizard\Components\WizardComponent;
use Zisunal\LaravelInstaller\Support\InstallLock;
use Zisunal\LaravelInstaller\Support\StepRegistry;
use Livewire\Attributes\Computed;

class InstallerWizard extends WizardComponent
{
    // public $currentStepIndex = 0;
    // public $progressPercent = 0;
    // public $currentStepClass;
    // public $currentStepState = [];
    
    #[Computed]
    public function steps(): array
    {
        return app(StepRegistry::class)->all();
    }

    public function mount(): void
    {
        abort_if( ! config( 'installer.enabled', true ), 404 );
        abort_if( InstallLock::installed(), 404 );
        // $this->currentStepClass = $this->steps()[0];
        // $this->progressPercent = 100 / count( $this->steps() );
    }


    // public function render()
    // {
    //     return view( 'installer::livewire.installer-wizard' );
    // }
}