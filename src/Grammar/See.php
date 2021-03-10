<?php

namespace MagicTest\MagicTest\Grammar;

use PhpParser\Node\Scalar\String_;

class See extends Grammar
{
    public function action(): string
    {
        // we have to trim the base string, which is enclosed with '
        $target = trim($this->target, "'");
        $target = trim($target);
        $target = "'" . $target . "'";

        return "->assertSee({$target})";
    }

    public function nameForParser()
    {
        return 'assertSee';
    }

    public function arguments()
    {
        $target = trim($this->target, "'");
        $target = trim($target);

        return [
            new String_($target),
        ];
    }
}
