<?php

namespace MagicTest\MagicTest;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use MagicTest\MagicTest\Grammar\Click;
use MagicTest\MagicTest\Grammar\Grammar;

class FileEditor
{
    protected static $writingTests = false;

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
        $arrayContent = explode("\n", $content);

        $firstAction = strtok(
            Str::of($content)
                ->after($method)
                ->after('$browser->')
                ->before("});\n")
                ->__toString(),
            "\n"
        );

        $newTestContent = collect([]);

        foreach ($arrayContent as $key => $line) {
            if ($this->isTestFirstAction($line, $firstAction)) {
                self::$writingTests = true;
                $newTestContent[] = Str::replaceLast(";", "", $line);
                $newTestContent[] = $this->buildGrammar($grammar);

                if (empty($arrayContent[$key + 1])) {
                    self::$writingTests = false;
                }

                continue;
            }

            if (self::$writingTests) {
                // if we are still writing tests but the line ends with a ;, then we stop "writing tests" and skipping lines.
                if (Str::endsWith(trim($line), ';')) {
                    self::$writingTests = false;

                    continue;
                } else {
                    // if we are still writing tests, it means this line is from the original file/previous test.
                    continue;
                }
            }

            // push the remaining lines.
            $newTestContent[] = $line;
        }

        return $newTestContent->flatten()->implode("\n");
    }

    protected function isTestFirstAction(string $line, string $firstAction): bool
    {
        return Str::contains(trim($line), trim($firstAction));
    }

    protected function buildGrammar(Collection $grammars): Collection
    {
        return $grammars->map(function (Grammar $grammar) use ($grammars) {
            $isLast = $grammar === $grammars->last();
            $text = [$grammar->build() . ($isLast ? ';' : '')];

            if ($grammar instanceof Click) {
                // this is a workaround since Dusk is not properly filling fields unless it waits a bit.
                $text[] = Grammar::indent('->pause(500)', 4);
            }
            
            return implode("\n", $text);
        });
    }
}
