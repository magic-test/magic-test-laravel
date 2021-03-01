<?php

namespace Mateusjatenee\MagicTest;

use Illuminate\Support\Collection;
use Mateusjatenee\MagicTest\Grammar\Grammar;

class MagicTestManager
{
    public static function run($browser)
    {
        MagicTest::setBrowserInstance($browser);

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
        $test = '';

        foreach ($grammar as $key => $g) {
            $isLast = ($key + 1) == $grammar->count();
            $test.= $g->build($isLast) . "\n";
        }

        dd($test);
    }
}