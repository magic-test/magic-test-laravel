<?php

namespace MagicTest\MagicTest\Parser;

class Line
{
    public function __construct(string $content, int $key = null)
    {
        $this->content = $content;
        $this->key = $key;
    }

    public function __toString(): string
    {
        return $this->content;
    }
}
