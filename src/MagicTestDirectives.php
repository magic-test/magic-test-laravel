<?php

namespace Mateusjatenee\MagicTest;

use Mateusjatenee\MagicTest\MagicTest;

class MagicTestDirectives
{
    public static function magicTestScripts(): string
    {
        return MagicTest::scripts();
    }
}