<?php

namespace MagicTest\MagicTest;

use Illuminate\Support\Collection;
use Laravel\Dusk\Browser;
use MagicTest\MagicTest\Grammar\Grammar;
use Spatie\Backtrace\Backtrace;

class MagicTestManager
{
    public static function run(Browser $browser)
    {
        $backtrace = collect(Backtrace::create()->withArguments()->limit(10)->frames());


        // this means it was called with the magic() macro
        if ($backtrace[3]->method === '__call') {
            $caller = $backtrace[6];
            $testMethod = $caller->method;
        } else {
            $callerKey = $backtrace[1]->method === 'magic_test' ? 2 : 1;
            $caller = $backtrace[$callerKey];
            $testMethod = $backtrace[$callerKey + 2]->method;
        }

        MagicTest::setBrowserInstance($browser);
        MagicTest::setTestMethod($testMethod);
        MagicTest::setOpenFile($caller->file);

        $browser->script('MagicTest.run()');

        $ok = new PendingOk;
        eval(\Psy\sh());
    }

    public function runScripts(): void
    {
        $browser = MagicTest::$browser;

        $output = json_decode($browser->driver->executeScript('return MagicTest.getData()'), true);
        $grammar = collect($output)->map(fn ($command) => Grammar::for($command));

        $this->buildTest($grammar);

        print($grammar->count() . " new actions were added to ". MagicTest::$file . "::" . MagicTest::$method);
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
