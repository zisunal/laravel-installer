<?php

namespace Zisunal\LaravelInstaller\Livewire\Steps;

class RequirementsStep extends AbstractInstallerStep
{
    public array $checks = [];

    public static function title(): string
    {
        return 'Requirements';
    }

    public static function description(): ?string
    {
        return 'Verify server requirements.';
    }

    public static function order(): int
    {
        return 2;
    }

    public function mount(): void
    {
        $reqs = config( 'installer.requirements', [] );
        $this->checks = [
            'php' => [
                'label' => "PHP version {$reqs['php']} or higher",
                'passed' => version_compare( PHP_VERSION, $reqs['php'], '>=' ),
            ],
        ];
        foreach ( $reqs['extensions'] ?? [] as $ext ) {
            $this->checks[ "ext_{$ext}" ] = [
                'label' => "PHP extension: {$ext}",
                'passed' => extension_loaded( $ext ),
            ];
        }
        foreach ( $reqs[ 'permissions' ] ?? [] as $label => $passed ) {
            $this->checks[ "perm_{$label}" ] = [
                'label' => "File permission: {$label}",
                'passed' => $passed,
            ];
        }
    }

    public function allPassed(): bool
    {
        return collect($this->checks)->every(fn ($check) => $check['passed'] === true);
    }

    public function next(): void
    {
        if (! $this->allPassed()) {
            return;
        }

        parent::next();
    }

    public function render()
    {
        return view('installer::livewire.steps.requirements');
    }
}