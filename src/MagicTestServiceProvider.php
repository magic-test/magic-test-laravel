<?php

namespace MagicTest\MagicTest;

use Illuminate\Support\Facades\Blade;
use Laravel\Dusk\Browser;
use MagicTest\MagicTest\Commands\MagicTestCommand;
use MagicTest\MagicTest\Controllers\MagicTestController;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MagicTestServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
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

    protected function registerRoutes()
    {
        if (MagicTest::running()) {
            return;
        }

        app('router')->post('/magic-test', MagicTestController::class);
    }
}
