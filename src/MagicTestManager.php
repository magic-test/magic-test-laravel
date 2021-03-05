<?php

namespace MagicTest\MagicTest;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use MagicTest\MagicTest\Grammar\Grammar;
use MagicTest\MagicTest\ShellCommands\Finish;
use MagicTest\MagicTest\ShellCommands\Ok;
use Psy\Configuration;
use Psy\Shell;
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

        $shell = new Shell(new Configuration([
            'startupMessage' => '<info>Your Magic Test session has started!</info>',
        ]));

        $shell->addCommands([
            new Ok,
            new Finish,
        ]);
        $shell->run();
    }

    public function runScripts(): string
    {
        $browser = MagicTest::$browser;

        $output = json_decode($browser->driver->executeScript('return MagicTest.getData()'), true);
        $grammar = collect($output)->map(fn ($command) => Grammar::for($command));

        if (is_null($grammar) || $grammar->isEmpty()) {
            return "No actions were added to " . MagicTest::$file . '::' . MagicTest::$method;
        }

        $this->buildTest($grammar);

        $browser->script('MagicTest.clear()');

        return $grammar->count() . " new " . Str::plural('action', $grammar->count()) . ($grammar->count() > 1 ? ' were' : ' was') . " added to ". MagicTest::$file . "::" . MagicTest::$method;
    }

    public function finish(): string
    {
        $content = file_get_contents(MagicTest::$file);
        $method = MagicTest::$method;

        file_put_contents(
            MagicTest::$file,
            (new FileEditor)->finish($content, $method)
        );

        return 'Your Magic Test session has finished. See you later!';
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
