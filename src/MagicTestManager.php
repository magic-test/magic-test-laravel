<?php

namespace Mateusjatenee\MagicTest;

use Illuminate\Support\Collection;
use Mateusjatenee\MagicTest\Grammar\Grammar;

class MagicTestManager
{
    public static function run($browser)
    {
        $backtrace = debug_backtrace();
        $caller = array_shift($backtrace);
        $testMethod = $backtrace[3]['function'];
        MagicTest::setBrowserInstance($browser);
        MagicTest::setTestMethod($testMethod);
        MagicTest::setOpenFile($caller['file']);

        eval(\Psy\sh());
    }

    public function runScripts()
    {
        $browser = MagicTest::$browser;

        $output = json_decode($browser->driver->executeScript('return MagicTest.getData()'), true);
        $grammar = collect($output)->map(fn ($command) => Grammar::for($command));

        $this->buildTest($grammar);
    }

    public function buildTest(Collection $grammar)
    {
        $content = file_get_contents(MagicTest::$file);
        $method = MagicTest::$method;
        
        file_put_contents(
            MagicTest::$file,
            (new FileEditor)->process($content, $grammar, $method)
        );
    }
}
