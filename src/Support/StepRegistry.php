<?php

namespace Zisunal\LaravelInstaller\Support;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use ReflectionClass;
use Zisunal\LaravelInstaller\Livewire\Steps\AbstractInstallerStep;

class StepRegistry
{
    public function all(): array
    {
        $manual = config('installer.steps', []);
        $discovered = config('installer.discover.enabled', true)
            ? $this->discover()
            : [];

        return collect(array_merge($manual, $discovered))
            ->filter(fn (string $class) => class_exists($class))
            ->filter(fn (string $class) => is_subclass_of($class, AbstractInstallerStep::class))
            ->reject(fn (string $class) => $this->isAbstract($class))
            ->unique()
            ->sortBy(fn (string $class) => $class::order())
            ->values()
            ->all();
    }

    public function discover(): array
    {
        $paths = config('installer.discover.paths', []);
        $classes = [];

        foreach ($paths as $path => $namespace) {
            if (! File::isDirectory($path)) {
                continue;
            }

            foreach (File::allFiles($path) as $file) {
                $class = $this->classFromFile($file->getRealPath(), $path, $namespace);

                if ($class) {
                    $classes[] = $class;
                }
            }
        }

        return $classes;
    }

    protected function classFromFile(string $file, string $basePath, string $namespace): ?string
    {
        $relative = Str::after(
            $file,
            rtrim(realpath($basePath) ?: $basePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR
        );

        $relativeClass = Str::of($relative)
            ->replaceLast('.php', '')
            ->replace(DIRECTORY_SEPARATOR, '\\')
            ->toString();

        $class = trim($namespace . '\\' . $relativeClass, '\\');

        return class_exists($class) ? $class : null;
    }

    protected function isAbstract(string $class): bool
    {
        return (new ReflectionClass($class))->isAbstract();
    }
}