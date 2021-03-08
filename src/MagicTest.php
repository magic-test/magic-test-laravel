<?php

namespace MagicTest\MagicTest;

use Laravel\Dusk\Browser;

class MagicTest
{
    public static $browser;

    public static $file;

    public static $method;

    public static function setBrowserInstance(Browser $browser): void
    {
        self::$browser = $browser;
    }

    public static function setOpenFile(string $file): void
    {
        self::$file = $file;
    }

    public static function setTestMethod(string $method): void
    {
        self::$method = $method;
    }

    public static function running(): bool
    {
        return config('magic-test-laravel')['running'] === true;
    }

    public function ok(): void
    {
        app(MagicTestManager::class)->runScripts();
    }

    public function flush()
    {
    }
    
    public static function scripts(): string
    {
        $script = file_get_contents(__DIR__ . '/../dist/magic_test.js');

        // HTML Label.
        $html = ['<!-- Magic Test Scripts -->'];
        // JavaScript assets.
        $html[] = '<script>';
        $html[] = config('app.debug') ? $script : self::minify($scripts);
        $html[] = '</script>';

        return implode("\n", $html);
    }

    protected static function minify(string $subject): string
    {
        return preg_replace('~(\v|\t|\s{2,})~m', '', $subject);
    }
}
