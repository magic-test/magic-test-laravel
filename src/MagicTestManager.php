<?php

namespace MagicTest\MagicTest;

use Laravel\Dusk\Browser;
use Illuminate\Support\Collection;
use MagicTest\MagicTest\Grammar\Grammar;

class MagicTestManager
{
    public static function run(Browser $browser)
    {
        $browser->script('MagicTest.run()');

        $backtrace = debug_backtrace();
        $caller = array_shift($backtrace);
        $testMethod = $backtrace[3]['function'];
        MagicTest::setBrowserInstance($browser);
        MagicTest::setTestMethod($testMethod);
        MagicTest::setOpenFile($caller['file']);



        eval(\Psy\sh());
    }

    public function runScripts(): void
    {
        $browser = MagicTest::$browser;

        $output = json_decode($browser->driver->executeScript('return MagicTest.getData()'), true);
        $grammar = collect($output)->map(fn ($command) => Grammar::for($command));

        $this->buildTest($grammar);
    }

    public function buildTest(Collection $grammar): void
    {
        $content = file_get_contents(MagicTest::$file);
        $method = MagicTest::$method;
        
        file_put_contents(
            MagicTest::$file,
            (new FileEditor)->process($content, $grammar, $method)
        );
    }
}
