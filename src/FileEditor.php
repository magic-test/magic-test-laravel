<?php

namespace MagicTest\MagicTest;

use Illuminate\Support\Collection;
use MagicTest\MagicTest\Parser\File;

class FileEditor
{
    public function finish(string $content, string $method): string
    {
        return File::fromContent($content, $method)->finish();
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
        return File::fromContent($content, $method)->addMethods($grammar);
    }
}
