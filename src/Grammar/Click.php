<?php

namespace MagicTest\MagicTest\Grammar;

use Illuminate\Support\Arr;

class Click extends Grammar
{
    public function action(): string
    {
        if (Arr::get($this->targetMeta, 'type') === 'checkbox') {
            return "->check({$this->target})";
        }

        return [
            'a' => "->clickLink({$this->target})",
            'button' => "->press({$this->target})",
            'default' => "->click({$this->target})",
        ][$this->tag] ?? "->click({$this->target})";
    }
}
