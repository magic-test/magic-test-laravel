<?php

namespace Mateusjatenee\MagicTest;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Mateusjatenee\MagicTest\MagicTest
 */
class MagicTestFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'magic-test-laravel';
    }
}
