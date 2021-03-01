<?php

namespace Mateusjatenee\MagicTest;

use Illuminate\Support\Facades\Blade;
use Spatie\LaravelPackageTools\Package;
use Mateusjatenee\MagicTest\Commands\MagicTestCommand;
use Mateusjatenee\MagicTest\Controllers\MagicTestController;
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
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_magic_test_laravel_table')
            ->hasCommand(MagicTestCommand::class);
    }

    public function boot() 
    {
        parent::boot();


        $this->app->singleton('magic-test-laravel', fn($app) => new MagicTest);

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
