<?php

namespace Mateusjatenee\MagicTest;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Mateusjatenee\MagicTest\Commands\MagicTestCommand;

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
}
