<?php

namespace Mateusjatenee\MagicTest\Tests;

use Mateusjatenee\MagicTest\Grammar\Click;
use Mateusjatenee\MagicTest\Grammar\See;
use Mateusjatenee\MagicTest\MagicTest;
use Mateusjatenee\MagicTest\MagicTestManager;

class MagicTestManagerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->testFilePaths = [
            __DIR__ . '/fixtures/ExampleTest.example',
            __DIR__ . '/fixtures/ExampleTestWithContent.example',
        ];

        $this->originalContents = [
            file_get_contents($this->testFilePaths[0]),
            file_get_contents($this->testFilePaths[1]),
        ];
    }

    public function tearDown(): void
    {
        foreach ($this->testFilePaths as $index => $path) {
            file_put_contents($path, $this->originalContents[$index]);
        }
    }

    /** @test */
    public function it_replaces_the_content_of_a_file_with_actions()
    {
        $expectedOutput = file_get_contents(__DIR__ . '/fixtures/ExampleTestOutput.example');

        MagicTest::setOpenFile($this->testFilePaths[1]);
        MagicTest::setTestMethod('testBasicExample');

        $input = file_get_contents(MagicTest::$file);

        $grammar = collect([
            new Click('click', '', "'Log in'", [], [], 'a'),
            new Click('click', '', "'Forgot your password?'", [], [], 'a'),
            new See('see', '', "'Mateus'", [], [], 'span'),
        ]);


        (new MagicTestManager)->buildTest($grammar);

        $this->assertEquals($expectedOutput, file_get_contents(MagicTest::$file));

        file_put_contents(MagicTest::$file, $input);
    }

    /** @test */
    public function it_replaces_the_content_of_a_file_without_actions()
    {
        $expectedOutput = file_get_contents(__DIR__ . '/fixtures/ExampleTestOutput.example');

        MagicTest::setOpenFile($this->testFilePaths[0]);
        MagicTest::setTestMethod('testBasicExample');

        $input = file_get_contents(MagicTest::$file);

        $grammar = collect([
            new Click('click', '', "'Log in'", [], [], 'a'),
            new Click('click', '', "'Forgot your password?'", [], [], 'a'),
            new See('see', '', "'Mateus'", [], [], 'span'),
        ]);


        (new MagicTestManager)->buildTest($grammar);

        $this->assertEquals($expectedOutput, file_get_contents(MagicTest::$file));

        file_put_contents(MagicTest::$file, $input);
    }
}
