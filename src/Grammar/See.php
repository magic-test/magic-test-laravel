<?php

namespace MagicTest\MagicTest\Grammar;

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
}
