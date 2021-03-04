<?php

namespace MagicTest\MagicTest;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use MagicTest\MagicTest\Grammar\Click;
use MagicTest\MagicTest\Grammar\Grammar;
use MagicTest\MagicTest\Parser\File;
use MagicTest\MagicTest\Parser\Line;

class FileEditor
{
    const MACRO = '->magic()';
    protected static $writingTests = false;

    protected $possibleMethods = ['MagicTestManager::run', 'magic_test', 'magic', 'm('];

    public function finish(string $content, string $method): string
    {
        $file = File::fromContent($content, $method);

        if ($file->breakpointLine->isMacroCall()) {
            $file->previousLineTo($file->breakpointLine)->final();
        }

        $file->removeLine($file->breakpointLine);

        return $file->freshOutput();
    }

    /**
     * Overwrites the current browser operations on a given content with new ones based on the given Grammar.
     *
     * @param string $content
     * @param \Illuminate\Support\Collection $grammar
     * @param string $method
     * @return string
     */
    public function process(string $content, Collection $grammar, string $method): string
    {
        $file = File::fromContent($content, $method);

        $file->forEachLine(function (Line $line, $key) use ($file, $grammar) {
            if (! $file->isLastAction($line)) {
                return;
            }

            $file->startWritingTest();

            if (! $line->isMacroCall()) {
                $line->removeSemicolon();
            }

            $grammar = $this->buildGrammar($grammar, $line->isMacroCall());
            $file->addTestLines($grammar);
            $file->stopWritingTest();

            return;
        });

        return $file->output();
    }

    protected function isTestFirstAction(string $line, string $firstAction): bool
    {
        return Str::contains(trim($line), trim($firstAction));
    }

    protected function isTestLastAction(Line $line, string $firstAction): bool
    {
        return Str::contains(trim((string) $line), trim($firstAction));
    }

    protected function buildGrammar(Collection $grammars, $endsWithMacro = false): Collection
    {
        return $grammars->map(function (Grammar $grammar) use ($grammars, $endsWithMacro) {
            $isLast = $grammar === $grammars->last();

            $needsPause = ($grammar instanceof Click && in_array($grammar->tag, ['a', 'button']));

            $text = [new Line($grammar->build())];

            return $text;

            return implode("\n", $text);
        })->flatten();
    }

    protected function isClickOrPress($line): bool
    {
        return Str::contains($line, ['click', 'clickLink', 'press']);
    }
}
