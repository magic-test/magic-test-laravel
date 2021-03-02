<?php

namespace MagicTest\MagicTest\Tests;

use MagicTest\MagicTest\FileEditor;
use MagicTest\MagicTest\Grammar\Click;
use MagicTest\MagicTest\Grammar\See;

class FileEditorTest extends TestCase
{
    /** @test */
    public function it_properly_replaces_the_method_content_when_it_does_not_have_actions()
    {
        $expectedInput = file_get_contents(__DIR__ . '/fixtures/ExampleTest.example');
        $expectedOutput = file_get_contents(__DIR__ . '/fixtures/ExampleTestOutput.example');
        

        $grammar = collect([
            new Click('', "'Log in'", [], [], 'a'),
            new Click('', "'Forgot your password?'", [], [], 'a'),
            new See('', "'Mateus'", [], [], 'span'),
        ]);

        $processedText = (new FileEditor)->process($expectedInput, $grammar, 'testBasicExample');

        $this->assertEquals($expectedOutput, $processedText);
    }

    /** @test */
    public function it_properly_replaces_the_content_when_it_has_actions()
    {
        $expectedInput = file_get_contents(__DIR__ . '/fixtures/ExampleTestWithContent.example');
        $expectedOutput = file_get_contents(__DIR__ . '/fixtures/ExampleTestWithContentOutput.example');
        

        $grammar = collect([
            new Click('', "'Forgot your password?'", [], [], 'a'),
            new See('', "'Mateus'", [], [], 'span'),
        ]);

        $processedText = (new FileEditor)->process($expectedInput, $grammar, 'testBasicExample');

        $this->assertEquals($expectedOutput, $processedText);
    }
}
