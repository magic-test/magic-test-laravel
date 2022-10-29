<?php

namespace MagicTest\MagicTest;

use Laravel\Dusk\Browser;
use MagicTest\MagicTest\Middleware\MagicTestMiddleware;
use MagicTest\MagicTest\Middleware\NullMagicTestMiddleware;

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

    public static function scripts(): string
    {
        $script = file_get_contents(__DIR__ . '/../dist/magic_test.js');

        // HTML Label.
        $html = ['<!-- Magic Test Scripts -->'];
        // JavaScript assets.
        $html[] = '<script>';
        $html[] = $script;
        $html[] = '</script>';

        return implode("\n", $html);
    }

    public static function disable(): void
    {
        app()->bind(MagicTestMiddleware::class, NullMagicTestMiddleware::class);
    }
}
