<?php

namespace MagicTest\MagicTest\Parser;

use Illuminate\Support\Str;
use MagicTest\MagicTest\FileEditor;
use MagicTest\MagicTest\Grammar\Grammar;

class Line
{
    const PAUSE = '->pause(500)';

    public $content;

    public $key;
    
    public function __construct(string $content, int $key = null)
    {
        $this->content = $content;
        $this->key = $key;
    }

    public static function indented(string $content, $indentation = 4): self
    {
        return new static(Grammar::indent($content, $indentation));
    }

    public static function pause(): self
    {
        return new static(Grammar::indent(self::PAUSE, 4));
    }

    public function removeSemicolon(): void
    {
        $this->content = Str::replaceLast(';', '', $this->content);
    }

    public function isMacroCall(): bool
    {
        return Str::contains($this->content, FileEditor::MACRO);
    }

    public function isHelper(): bool
    {
        return $this->isMacroCall() || Str::contains($this->content, ['magic_test(', 'magic(']);
    }

    public function isClickOrPress(): bool
    {
        return Str::contains($this->content, ['click(', 'clickLink(', 'press(']);
    }

    public function isVisit(): bool
    {
        return Str::contains($this->content, 'visit(');
    }

    public function isPause(): bool
    {
        return Str::contains($this->content, 'pause(');
    }

    public function isEmpty(): bool
    {
        return empty(trim($this->content));
    }

    public function final(): self
    {
        $this->content .= ';';

        return $this;
    }

    public function notFinal(): self
    {
        $this->content = Str::replaceLast(';', '', $this->content);

        return $this;
    }

    public function isFinal(): bool
    {
        return Str::endsWith($this->content, ';');
    }

    public function __toString(): string
    {
        return $this->content;
    }
}
