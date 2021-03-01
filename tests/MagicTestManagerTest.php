<?php

namespace Mateusjatenee\MagicTest\Tests;

use Mateusjatenee\MagicTest\Grammar\Click;
use Mateusjatenee\MagicTest\MagicTest;
use Mateusjatenee\MagicTest\MagicTestManager;

class MagicTestManagerTest extends TestCase
{
    /** @test */
    public function it_replaces_the_content_of_a_file()
    {
        $expectedOutput = file_get_contents(__DIR__ . '/fixtures/ExampleTestOutput.example');

        MagicTest::setOpenFile(__DIR__ . '/fixtures/ExampleTest.example');
        MagicTest::setTestMethod('testBasicExample');

        $grammar = collect([
            new Click('click', '', 'Log in', '', [], [], 'a'),
            new Click('click', '', 'Forgot your password?', [], [], 'a'),
        ]);

        $output = (new MagicTestManager)->buildTest($grammar);

        dd($output);
    }
}
