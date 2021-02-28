<?php

namespace Mateusjatenee\MagicTest;

class MagicTest
{
    public static function running(): bool
    {
        return config('magic-test-laravel')['running'] === true;
    }
    
    public static function scripts(): string
    {
        $script = file_get_contents(__DIR__ . '/../js/magic_test.js');

        // HTML Label.
        $html = ['<!-- Magic Test Scripts -->'];

        // JavaScript assets.
        $html[] = config('app.debug') ? $script : $this->minify($scripts);

        return implode("\n", $html);
    }

    protected function minify($subject): string
    {
        return preg_replace('~(\v|\t|\s{2,})~m', '', $subject);
    }
}
