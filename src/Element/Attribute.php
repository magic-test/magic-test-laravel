<?php

namespace MagicTest\MagicTest\Element;

class Attribute
{
    public string $name;

    public string $value;

    public bool $isUnique;

    public function __construct($name, $value, $isUnique = true)
    {
        $this->name = $name;
        $this->value = $value;
        $this->isUnique = $isUnique;
    }

    public function isUnique(): bool
    {
        return $this->isUnique;
    }

    public function buildSelector(): string
    {
        return [
            'name' => $this->value,
            'id' => "#{$this->value}",
        ][$this->name] ?? "input[{$this->name}={$this->value}]";
    }
}
