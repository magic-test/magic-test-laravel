<?php

namespace MagicTest\MagicTest\Grammar;

use PhpParser\Node\Scalar\String_;

class See extends Grammar
{
    public function nameForParser()
    {
        return 'assertSee';
    }

    public function arguments()
    {
        return [
            new String_($this->getMeta('text')),
        ];
    }
}
