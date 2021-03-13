<?php

namespace MagicTest\MagicTest\Grammar;

use PhpParser\Node\Scalar\String_;

class Fill extends Grammar
{
    public function nameForParser()
    {
        return 'type';
    }

    public function arguments()
    {
        return [
            new String_(trim($this->target, "'")),
            new String_(trim($this->options['text'], "'")),
        ];
    }

    public function pause()
    {
        if ($this->isLivewire()) {
            return new Pause(200);
        }
    }
}
