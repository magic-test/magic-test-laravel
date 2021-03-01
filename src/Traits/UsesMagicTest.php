<?php

namespace MagicTest\MagicTest\Traits;

trait UsesMagicTest
{
    public function setUp(): void
    {
        parent::setUp();

        putenv('DUSK_HEADLESS_DISABLED=true');
    }
}
