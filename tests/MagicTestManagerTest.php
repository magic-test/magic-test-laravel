<?php

namespace MagicTest\MagicTest\Tests;

use MagicTest\MagicTest\Grammar\Click;
use MagicTest\MagicTest\Grammar\See;
use MagicTest\MagicTest\MagicTest;
use MagicTest\MagicTest\MagicTestManager;

class MagicTestManagerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->testFilePaths = [
            __DIR__ . '/fixtures/Regular/input.php',
            __DIR__ . '/fixtures/WithActions/input.php',
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
        $expectedOutput = file_get_contents(__DIR__ . '/fixtures/WithActions/output.php');

        MagicTest::setOpenFile($this->testFilePaths[1]);
        MagicTest::setTestMethod('testBasicExample');

        $input = file_get_contents(MagicTest::$file);

        $grammar = collect([
            new Click('', "'Forgot your password?'", [], [], 'a'),
            new See('', "'Mateus'", [], [], 'span'),
        ]);

        (new MagicTestManager)->buildTest($grammar);

        $this->assertEquals($expectedOutput, file_get_contents(MagicTest::$file));

        file_put_contents(MagicTest::$file, $input);
    }

    /** @test */
    public function it_replaces_the_content_of_a_file_without_actions()
    {
        $expectedOutput = file_get_contents(__DIR__ . '/fixtures/Regular/output.php');

        MagicTest::setOpenFile($this->testFilePaths[0]);
        MagicTest::setTestMethod('testBasicExample');

        $input = file_get_contents(MagicTest::$file);

        $grammar = collect([
            new Click('', "'Log in'", [], [], 'a'),
            new Click('', "'Forgot your password?'", [], [], 'a'),
            new See('', "'Mateus'", [], [], 'span'),
        ]);


        (new MagicTestManager)->buildTest($grammar);

        $this->assertEquals($expectedOutput, file_get_contents(MagicTest::$file));

        file_put_contents(MagicTest::$file, $input);
    }
}
