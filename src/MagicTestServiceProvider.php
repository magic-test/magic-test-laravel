<?php

namespace MagicTest\MagicTest;

use Illuminate\Support\Facades\Blade;
use Laravel\Dusk\Browser;
use MagicTest\MagicTest\Commands\MagicTestCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MagicTestServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('magic-test-laravel')
            ->hasCommand(MagicTestCommand::class);
    }

    public function boot()
    {
        parent::boot();

        $this->app->singleton('magic-test-laravel', fn ($app) => new MagicTest);

        Browser::macro('magic', fn () => MagicTestManager::run($this));
        Blade::directive('magicTestScripts', [MagicTest::class, 'scripts']);
    }
}
