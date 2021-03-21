<?php

namespace MagicTest\MagicTest\Tests;

use MagicTest\MagicTest\MagicTestServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            MagicTestServiceProvider::class,
        ];
    }
}
