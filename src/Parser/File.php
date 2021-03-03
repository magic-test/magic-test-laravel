<?php

namespace MagicTest\MagicTest\Parser;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class File
{
    const MACRO = '->magic()';

    public $content;
    public $method;

    public $lines;

    public $stopTestsBeforeKey;

    public $currentLineInIteration;

    public $writingTest = false;

    public $lastLineAdded;

    protected $possibleMethods = ['MagicTestManager::run', 'magic_test', 'magic', 'm('];

    public function __construct(string $content, string $method)
    {
        $this->content = $content;
        $this->method = $method;
        $this->lines = $this->generateLines();
    }

    public static function fromContent(string $content, string $method): self
    {
        return new static($content, $method);
    }

    public function getLastAction(): Line
    {
        $a = [];
        $fullMethod = 'public function ' . $this->method;

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

    public function isLastAction(Line $line): bool
    {
        return Str::contains(trim($line), trim($this->getLastAction()));
    }

    public function forEachLine(callable $closure)
    {
        foreach ($this->lines as $key => $line) {
            $this->currentLineInIteration = $line;
            $closure($line, $key);
        }
    }

    public function addContentAfterLine(Line $referenceLine, Line $newLine): void
    {
        $this->lines = $this->lines->map(function (Line $line, $key) use ($referenceLine, $newLine) {
            if ($line == $referenceLine) {
                return [$line, $newLine];
            }

            return $line;
        })->flatten();

        $this->lastLineAdded = $newLine;
    }

    public function startWritingTest(): void
    {
        $this->testStartsAtLine = $this->currentLineInIteration;
        $this->writingTest = true;
    }

    public function stopWritingTest(): void
    {
        $this->writingTest = false;
    }

    protected function generateLines(): Collection
    {
        $lines = explode("\n", $this->content);

        return  collect($lines)->mapInto(Line::class);
    }
}
