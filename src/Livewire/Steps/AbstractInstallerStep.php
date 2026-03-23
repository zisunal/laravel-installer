<?php

namespace Zisunal\LaravelInstaller\Livewire\Steps;

use Livewire\Component;
use Spatie\LivewireWizard\Components\StepComponent;
use Zisunal\LaravelInstaller\Support\InstallerManager;

abstract class AbstractInstallerStep extends StepComponent
{
    public static function slug(): string
    {
        return str()->slug(class_basename(static::class))->toString();
    }

    public static function title(): string
    {
        return class_basename(static::class);
    }

    public static function description(): ?string
    {
        return null;
    }

    public static function icon(): string
    {
        return 'heroicon-o-square-3-stack-3d';
    }

    public static function order(): int
    {
        return 0;
    }

    public function stepInfo(): array
    {
        return [
            'label' => static::title(),
            'icon' => static::icon(),
            'description' => static::description(),
        ];
    }

    protected function rules(): array
    {
        return [];
    }

    protected function payload(): array
    {
        return [];
    }

    public function previous(): void
    {
        $this->previousStep();
    }

    public function next(): void
    {
        if ($this->rules()) {
            $this->validate($this->rules());
        }

        $this->nextStep();
    }

    protected function allState(): array
    {
        return $this->state()->all();
    }
}