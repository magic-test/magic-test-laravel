<?php

namespace MagicTest\MagicTest\Grammar;

use PhpParser\Node\Scalar\LNumber;

class Pause
{
    public function __construct($time = 500)
    {
        $this->time = $time;
    }

    public function nameForParser()
    {
        return 'pause';
    }

    public function arguments()
    {
        return [new LNumber($this->time)];
    }
}
