<?php

namespace Mateusjatenee\MagicTest;

use Illuminate\Support\Str;
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

        eval(\Psy\sh());;
    }

    public function runScripts()
    {
        $browser = MagicTest::$browser;

        $output = json_decode($browser->driver->executeScript('return MagicTest.getData()'), true);
        $grammar = collect($output)->map(fn($command) => Grammar::for($command));

        $test = $this->buildTest($grammar);
        dd($test);
    }

    public function buildTest(Collection $grammar)
    {
        $test = "\n";

        foreach ($grammar as $key => $g) {
            $isLast = ($key + 1) == $grammar->count();
            $test.= $g->build($isLast) . ($isLast ? '' : "\n");
        }
        $file = file_get_contents(MagicTest::$file);

        $after = Str::of($file)
            ->after(MagicTest::$method)
            ->after('$browser->')
            ->before("\n");


        $toReplace = (string) Str::of($file)
            ->after(MagicTest::$method)
            ->after($after)
            ->before(');') . ');';

        $newContent = Str::replaceFirst($toReplace, $test, $file);

        
        file_put_contents(MagicTest::$file, $newContent);

        // $content = preg_replace((?<=visit\(\'/.'*)(.*)(?=;), $test, $file);
        // dd($content);
        // // $regex = /.+?(?=abc)/;
    }
}