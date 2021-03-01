<?php

namespace Mateusjatenee\MagicTest;

use Laravel\Dusk\Browser;

class MagicTest
{
    public static $browser;

    public static function setBrowserInstance(Browser $browser)
    {
        self::$browser = $browser;
    }

    public static function running(): bool
    {
        return config('magic-test-laravel')['running'] === true;
    }

    public function ok()
    {
        return app(MagicTestManager::class)->runScripts();
    }

    public function flush()
    {
        
    }
    
    public static function scripts(): string
    {
        $script = file_get_contents(__DIR__ . '/../js/magic_test.js');

        // HTML Label.
        $html = ['<!-- Magic Test Scripts -->'];
        $html[] = '<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>';
        // JavaScript assets.
        $html[] = '<script>';
        $html[] = config('app.debug') ? $script : $this->minify($scripts);
        $html[] = '</script>';

        return implode("\n", $html);
    }

    protected function minify($subject): string
    {
        return preg_replace('~(\v|\t|\s{2,})~m', '', $subject);
    }
}
