<?php

namespace MagicTest\MagicTest\Grammar;

use Illuminate\Support\Arr;

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
            'default' => "->click({$this->target})",
        ][$this->tag] ?? "->click({$this->target})";
    }
}
