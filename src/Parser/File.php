<?php

namespace MagicTest\MagicTest\Parser;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class File
{
    const MACRO = '->magic()';

    public string $content;

    public string $method;

    public Collection $lines;

    public Line $initialMethodLine;

    public Line $breakpointLine;

    public Line $lastActionLine;

    public ?Line $testStartsAtLine;

    public ?Line $currentLineInIteration;

    public bool $writingTest = false;

    public ?Line $lastLineAdded;

    protected array $possibleMethods = ['magic_test', '->magic()'];

    public function __construct(string $content, string $method)
    {
        $this->content = $content;
        $this->method = $method;
        $this->lines = $this->generateLines();
        $this->lastLineAdded = $this->getLastAction();
    }

    public static function fromContent(string $content, string $method): self
    {
        return new static($content, $method);
    }

    public function getLastAction(): Line
    {
        $fullMethod = 'public function ' . $this->method;

        $this->initialMethodLine = $this->lines
                            ->filter(fn (Line $line) => Str::contains($line, $fullMethod))
                            ->first();


        $this->breakpointLine = $this->lines
                            ->skipUntil(fn (Line $line) => $line === $this->initialMethodLine)
                            ->filter(fn (Line $line) => Str::contains($line, $this->possibleMethods))
                            ->first();


        $this->lastActionLine = $this->reversedLines()
                        ->skipUntil(fn (Line $line) => $line === $this->breakpointLine)
                        ->skip(1)
                        ->takeUntiL(fn (Line $line) => $line === $this->initialMethodLine)
                        ->reject(fn (Line $line) => $line->isEmpty())
                        ->first();


        return $this->lastActionLine;
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

    public function testLines(): Collection
    {
        return $this->lines
            ->skipUntil($this->initialMethodLine)
            ->takeUntil($this->breakpointLine)
            ->reject(fn (Line $line) => $line->isEmpty());
    }

    public function forEachTestLine(callable $closure)
    {
        foreach ($this->testLines() as $line) {
            $closure($line);
        }
    }

    public function addTestLine(Line $line, $final = false): void
    {
        $this->addContentAfterLine($this->lastLineAdded, $line, $final);
    }

    public function addTestLines($lines): void
    {
        collect($lines)->each(fn (Line $line, $key) => $this->addTestLine($line, $line === $lines->last()));
    }

    public function removeLine(Line $line): void
    {
        $this->lines = $this->lines->reject(
            fn (Line $originalLine) =>
             $originalLine == $line
        );
    }

    public function addContentAfterLine(Line $referenceLine, Line $newLine, $final = false, $methodCallsOnly = false): void
    {
        // There is an edge case where an "assertSee" method may have more than one line.
        // The issue is figuring out where to stop... so when the output() method is
        // called, we pass "methodCallsOnly" as true to try and find the correct
        // ending line of that method call, by checking if it ends on );
        // instead of just picking the refrence line.
        $desiredLine = $this->lines->skipUntil(function (Line $line, $key) use ($referenceLine, $newLine, $final) {
            return $line === $referenceLine;
        })->first(function (Line $line, $key) use ($methodCallsOnly) {
            return $methodCallsOnly ? $line->isMethodEnding() : true;
        });


        $this->lines = $this->lines->map(function (Line $line, $key) use ($desiredLine, $newLine, $final) {
            if ($line !== $desiredLine) {
                return $line;
            }
            

            if ($final) {
                $newLine->final();
            }

            $return = [$line, $newLine];

            $this->lastLineAdded = last($return);

            return $return;
        })->flatten();
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

    public function previousLineTo(Line $line, $ignoreHelpers = true): Line
    {
        $lineKey = $this->reversedLines()->search($line);

        return $this->reversedLines()->filter(
            fn (Line $line, $key) =>
            $key > $lineKey && ($ignoreHelpers ? ! $line->isHelper() : true)
        )->first();
    }

    public function isFirstClick(Line $line): bool
    {
        $reversedLines = $this->reversedLines();

        return $this->reversedLines()
                    ->skipUntil(fn (Line $foundLine) => $foundLine === $line)
                    ->skip(1)
                    ->takeUntil(fn (Line $foundLine) => $foundLine === $this->initialMethodLine)
                    ->filter(fn (Line $line) => $line->isClickOrPress())
                    ->isEmpty();
    }

    public function reversedLines(): Collection
    {
        return $this->lines->reverse()->values();
    }

    public function freshOutput(): string
    {
        return $this->lines
                ->map(fn (Line $line) => $line->__toString())
                ->implode(PHP_EOL);
    }

    public function output(): string
    {
        $lines = clone $this->lines;

        $this->fixBreakpoint();
        $this->addNecessaryPausesToLines();
        $this->removeDuplicatePauses();

        return tap(
            $this->lines
            ->map(fn (Line $line) => $line->__toString())
            ->implode(PHP_EOL),
            fn () => $this->lines = $lines
        );
    }

    /**
     * After clicks and presses, we need to add a pause(500) call so the browsing
     * works properly. This method iterates through all the lines and
     * adds the pause calls where necessary.
     *
     * @return void
     */
    public function addNecessaryPausesToLines(): void
    {
        $this->forEachTestLine(function ($line) {
            $previousLine = $this->previousLineTo($line);

            if ($previousLine->requiresPause()) {
                $this->addContentAfterLine($previousLine, Line::pause(), false, true);
            }
        });
    }

    public function removeDuplicatePauses(): void
    {
        $this->forEachTestLine(function ($line) {
            $previousLine = $this->previousLineTo($line);

            if ($previousLine->isPause() && $line->isPause()) {
                $this->removeLine($line);
            }
        });
    }

    /**
     * Depending wether the user used the fluent magic() call or the magic_test helper,
     * we need to appropriately place semicolons. If the magic helper was used, we
     * need to remove the semicolon from the line previous to it, since it is
     * the one-to-last call on the chain.
     *
     * @return void
     */
    public function fixBreakpoint(): void
    {
        if (optional($this->breakpointLine)->isMacroCall()) {
            $this->previousLineTo($this->breakpointLine)->notFinal();
        }
    }

    protected function generateLines(): Collection
    {
        $lines = explode(PHP_EOL, $this->content);

        return  collect($lines)->mapInto(Line::class);
    }
}
