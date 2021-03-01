<?php

namespace MagicTest\MagicTest\Grammar;

class Click extends Grammar
{
    public function action(): string
    {
        if ($this->tag === 'a') {
            return "->clickLink({$this->target})";
        }

        return "->click({$this->target})";
    }
}
