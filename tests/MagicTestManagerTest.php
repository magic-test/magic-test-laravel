<?php

namespace Mateusjatenee\MagicTest\Tests;

use Mateusjatenee\MagicTest\MagicTest;
use Mateusjatenee\MagicTest\Grammar\See;
use Mateusjatenee\MagicTest\Grammar\Click;
use Mateusjatenee\MagicTest\MagicTestManager;

class MagicTestManagerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->testFilePath = __DIR__ . '/fixtures/ExampleTest.example';
        $this->originalContent = file_get_contents($this->testFilePath);
    }

    public function tearDown(): void
    {
        file_put_contents($this->testFilePath, $this->originalContent);
    }

    /** @test */
    public function it_replaces_the_content_of_a_file()
    {
        $expectedOutput = file_get_contents(__DIR__ . '/fixtures/ExampleTestOutput.example');

        MagicTest::setOpenFile($this->testFilePath);
        MagicTest::setTestMethod('testBasicExample');

        $input = file_get_contents(MagicTest::$file);

        $grammar = collect([
            new Click('click', '', 'Log in', [], [], 'a'),
            new Click('click', '', 'Forgot your password?', [], [], 'a'),
            new See('see', '', 'Mateus', [], [], 'span')
        ]);


        (new MagicTestManager)->buildTest($grammar);

        $this->assertEquals($expectedOutput, file_get_contents(MagicTest::$file));

        file_put_contents(MagicTest::$file, $input);
    }
}
