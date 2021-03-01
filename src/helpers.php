<?php

use Mateusjatenee\MagicTest\MagicTest;
use Mateusjatenee\MagicTest\MagicTestManager;


function ok()
{
    return app('magic-test')->ok();
}



function flush()
{
    return MagicTest::flush();
}



function magic_test()
{
    return MagicTestManager::run();
}
