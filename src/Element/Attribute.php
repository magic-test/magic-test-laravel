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

    public function buildSelector($element = 'input', $forceInputSyntax = false): string
    {
        return [
            'dusk' => "@{$this->value}",
            'name' => $forceInputSyntax ? $this->buildFullSelector($element) : $this->value,
            'id' => $forceInputSyntax ? $this->buildFullSelector($element) : "#{$this->value}",
        ][$this->name] ?? $this->buildFullSelector($element);
    }

    public function buildFullSelector(string $element): string
    {
        return "{$element}[{$this->name}={$this->value}]";
    }
}
