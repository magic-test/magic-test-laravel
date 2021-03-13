<?php

namespace MagicTest\MagicTest\Grammar;

use Illuminate\Support\Arr;
use PhpParser\Node\Scalar\String_;

class Click extends Grammar
{
    public function nameForParser()
    {
        if (Arr::get($this->meta, 'type') === 'checkbox') {
            return 'check';
        } elseif (Arr::get($this->meta, 'type') === 'radio') {
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
        if (Arr::get($this->meta, 'type') === 'radio') {
            $label = Arr::get($this->meta, 'label');

            // we remove it since we are going to put it under a selector (e.g: input[name=foo])
            // and we need to enclose the whole thing instead of just the target.
            $strippedTagsTarget = trim($this->target, "'");

            return [
                new String_("input[name={$strippedTagsTarget}]"),
                new String_($label),
            ];
        } elseif ($this->tag === 'select') {
            $label = Arr::get($this->meta, 'label');

            return [
                new String_(trim($this->target, "'")),
                new String_($label),
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
}
