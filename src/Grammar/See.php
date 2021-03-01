<?php

namespace Mateusjatenee\MagicTest\Grammar;

class See extends Grammar
{
    public function action(): string
    {
        return "->assertSee({$this->target})";
    }
}