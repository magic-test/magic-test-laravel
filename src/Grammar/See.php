<?php

namespace MagicTest\MagicTest\Grammar;

class See extends Grammar
{
    public function action(): string
    {
        return "->assertSee({$this->target})";
    }
}
