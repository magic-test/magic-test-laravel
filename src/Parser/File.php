<?php

namespace MagicTest\MagicTest\Parser;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class File
{
    const MACRO = '->magic()';

    public $content;
    public $lines;

    protected $possibleMethods = ['MagicTestManager::run', 'magic_test', 'magic', 'm('];

    public function __construct(string $content)
    {
        $this->content = $content;
        $this->lines = $this->generateLines();
    }

    public static function fromContent(string $content): self
    {
        return new static($content);
    }

    public function getLastAction(string $method): Line
    {
        $a = [];
        $fullMethod = 'public function ' . $method;

        foreach ($this->lines as $key => $line) {
            if (Str::contains((string) $line, $fullMethod)) {
                $reachedTestCase = true;
                $testCaseKey = $key;

                $breakpointKey = null;
                foreach ($this->lines as $bKey => $line) {
                    if (Str::contains($line, $this->possibleMethods)) {
                        $breakpointKey = $bKey;
                        $breakpointType = trim($line) === '->magic();' ? 'macro' : 'regular';
                    }
                }
            }
        }

        $lastAction = $this->lines->filter(function ($line, $key) use ($testCaseKey, $breakpointKey, $breakpointType) {
            if ($breakpointType === 'macro') {
                return $key > $testCaseKey && $key <= $breakpointKey && Str::endsWith($line, ';');
            }

            return $key > $testCaseKey && $key < $breakpointKey && Str::endsWith($line, ';');
        })->first();

        return $lastAction;
    }

    protected function generateLines(): Collection
    {
        $lines = explode("\n", $this->content);

        return  collect($lines)->mapInto(Line::class);
    }
}
