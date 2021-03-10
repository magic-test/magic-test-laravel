<?php

namespace MagicTest\MagicTest\Grammar;

use Illuminate\Support\Arr;
use PhpParser\Node\Scalar\String_;
use MagicTest\MagicTest\Grammar\Pause;

class Click extends Grammar
{
    public function action(): string
    {
        if (Arr::get($this->targetMeta, 'type') === 'checkbox') {
            return "->check({$this->target})";
        } elseif (Arr::get($this->targetMeta, 'type') === 'radio') {
            $label = Arr::get($this->targetMeta, 'label');

            // we remove it since we are going to put it under a selector (e.g: input[name=foo])
            // and we need to enclose the whole thing instead of just the target.
            $strippedTagsTarget = trim($this->target, "'");

            return "->radio('input[name={$strippedTagsTarget}]', '{$label}')";
        } elseif ($this->tag === 'select') {
            $label = Arr::get($this->targetMeta, 'label');

            return "->select({$this->target}, '{$label}')";
        }

        return [
            'a' => "->clickLink({$this->target})",
            'button' => "->press({$this->target})",
            'div' => "->press({$this->target})",
            'default' => "->click({$this->target})",
        ][$this->tag] ?? "->click({$this->target})";
    }

    public function nameForParser()
    {
        if (Arr::get($this->targetMeta, 'type') === 'checkbox') {
            return 'check';
        } elseif (Arr::get($this->targetMeta, 'type') === 'radio') {
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
        if (Arr::get($this->targetMeta, 'type') === 'radio') {
            $label = Arr::get($this->targetMeta, 'label');

            // we remove it since we are going to put it under a selector (e.g: input[name=foo])
            // and we need to enclose the whole thing instead of just the target.
            $strippedTagsTarget = trim($this->target, "'");

            return [
                new String_("input[name={$strippedTagsTarget}]"),
                new String_($label)
            ];
        } elseif ($this->tag === 'select') {
            $label = Arr::get($this->targetMeta, 'label');

            return [
                new String_(trim($this->target, "'")),
                new String_($label)
            ];
        }

        $target = trim($this->target, "'");
        $target = trim($target);

        return [
            new String_($target)
        ];
    }

    public function pause()
    {
        return new Pause(200);
    }
}
