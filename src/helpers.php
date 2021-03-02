<?php

use MagicTest\MagicTest\MagicTest;
use MagicTest\MagicTest\MagicTestManager;

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
    function magic_test(...$arguments)
    {
        return MagicTestManager::run(...$arguments);
    }
}

if (! function_exists('magic')) {
    function magic(...$arguments)
    {
        return MagicTestManager::run(...$arguments);
    }
}


if (! function_exists('mt')) {
    function mt(...$arguments)
    {
        return MagicTestManager::run(...$arguments);
    }
}
