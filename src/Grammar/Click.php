<?php

namespace Mateusjatenee\MagicTest\Grammar;

class Click extends Grammar
{
    public function action()
    {
        if ($this->tag === 'a') {
            return "->clickLink('{$this->target}')";
        }

        return "->click('{$this->target}')";
    }
}
