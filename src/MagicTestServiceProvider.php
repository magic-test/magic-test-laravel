<?php

namespace MagicTest\MagicTest;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Laravel\Dusk\Browser;
use MagicTest\MagicTest\Commands\MagicTestCommand;
use MagicTest\MagicTest\Middleware\MagicTestMiddleware;
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
        
        $this->app['router']->pushMiddlewareToGroup('web', MagicTestMiddleware::class);

        Browser::macro('magic', fn () => MagicTestManager::run($this));
        Browser::macro('clickElement', function ($selector, $value) {
            foreach ($this->resolver->all($selector) as $element) {
                if (Str::contains($element->getText(), $value)) {
                    $element->click();

                    return $this;
                }
            }

            throw new InvalidArgumentException(
                "Unable to locate element [${selector}] with content [{$value}]."
            );
        });

        Blade::directive('magicTestScripts', [MagicTest::class, 'scripts']);
    }
}
