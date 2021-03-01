<?php

namespace Mateusjatenee\MagicTest\Grammar;

class Fill extends Grammar
{
    public function action(): string
    {
        return "->type({$this->target}, {$this->options['text']})";
    }
}
