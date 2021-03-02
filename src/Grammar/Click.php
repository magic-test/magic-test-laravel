<?php

namespace MagicTest\MagicTest\Grammar;

class Click extends Grammar
{
    public function action(): string
    {
        return [
            'a' => "->clickLink({$this->target})",
            'button' => "->press({$this->target})",
            'default' => "->click({$this->target})",
        ][$this->tag] ?? "->click({$this->target})";
    }
}
