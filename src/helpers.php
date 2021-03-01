<?php

use Mateusjatenee\MagicTest\MagicTest;
use Mateusjatenee\MagicTest\MagicTestManager;

if (! function_exists('ok')) {
    function ok()
    {
        return app('magic-test')->ok();
    }
}


if (! function_exists('flush')) {
    function flush()
    {
        return MagicTest::flush();
    }
}


if (! function_exists('magic_test')) {
    function magic_test()
    {
        return MagicTestManager::run();
    }
}
