<?php

namespace MagicTest\MagicTest\Parser;

use Illuminate\Support\Str;
use MagicTest\MagicTest\FileEditor;
use MagicTest\MagicTest\Grammar\Grammar;

class Line
{
    public function __construct(string $content, int $key = null)
    {
        $this->content = $content;
        $this->key = $key;
    }

    public static function indented(string $content, $indentation = 4): self
    {
        return new static(Grammar::indent($content, $indentation));
    }

    public function removeSemicolon(): void
    {
        $this->content = Str::replaceLast(';', '', $this->content);
    }

    public function isMacroCall(): bool
    {
        return Str::contains($this->content, FileEditor::MACRO);
    }

    public function isClickOrPress(): bool
    {
        return Str::contains($this->content, ['click', 'clickLink', 'press']);
    }

    public function final(): self
    {
        $this->content .= ';';

        return $this;
    }

    public function __toString(): string
    {
        return $this->content;
    }
}
