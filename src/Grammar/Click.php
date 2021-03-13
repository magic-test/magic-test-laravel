<?php

namespace MagicTest\MagicTest\Grammar;

use PhpParser\Node\Scalar\String_;

class Click extends Grammar
{
    public function nameForParser()
    {
        if ($this->getMeta('type') === 'checkbox') {
            return 'check';
        } elseif ($this->getMeta('type') === 'radio') {
            return 'radio';
        } elseif ($this->tag === 'select') {
            return 'select';
        }

        return [
            'a' => "clickLink",
            'button' => 'press',
            'div' => "press",
            'default' => "click",
        ][$this->tag] ?? "click";
    }

    public function arguments()
    {
        if ($this->getMeta('type') === 'radio') {
            return [
                new String_($this->selector(true)),
                new String_($this->getMeta('label')),
            ];
        } elseif ($this->tag === 'select') {
            return [
                new String_($this->selector()),
                new String_($this->getMeta('label')),
            ];
        }

        return [
            new String_($this->getMeta('text')),
        ];
    }

    public function pause()
    {
        return new Pause(500);
    }

    public function selector($forceInput = false): string
    {
        $attribute = $this->attributes->filter->isUnique()->first() ??
                    $this->attributes->first();
        
        return $attribute->buildSelector($forceInput);
    }
}
