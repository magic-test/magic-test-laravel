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
            new String_($this->selector()),
            new String_($this->meta['text']),
        ];
    }

    public function pause()
    {
        if ($this->isLivewire()) {
            return new Pause(200);
        }
    }

    public function selector(): string
    {
        $attribute = $this->attributes->filter->isUnique()->first() ??
                    $this->attributes->first();
        
        return $attribute->buildSelector();
    }
}
