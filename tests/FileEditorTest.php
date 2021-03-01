<?php

namespace Mateusjatenee\MagicTest\Tests;

use Mateusjatenee\MagicTest\FileEditor;
use Mateusjatenee\MagicTest\Grammar\Click;
use Mateusjatenee\MagicTest\Grammar\See;

class FileEditorTest extends TestCase
{
    /** @test */
    public function it_properly_replaces_the_method_content_when_it_does_not_have_actions()
    {
        $expectedInput = file_get_contents(__DIR__ . '/fixtures/ExampleTest.example');
        $expectedOutput = file_get_contents(__DIR__ . '/fixtures/ExampleTestOutput.example');
        

        $grammar = collect([
            new Click('click', '', "'Log in'", [], [], 'a'),
            new Click('click', '', "'Forgot your password?'", [], [], 'a'),
            new See('see', '', "'Mateus'", [], [], 'span'),
        ]);

        $processedText = (new FileEditor)->process($expectedInput, $grammar, 'testBasicExample');

        $this->assertEquals($expectedOutput, $processedText);
    }

    /** @test */
    public function it_properly_replaces_the_content_when_it_has_actions()
    {
        $expectedInput = file_get_contents(__DIR__ . '/fixtures/ExampleTestWithContent.example');
        $expectedOutput = file_get_contents(__DIR__ . '/fixtures/ExampleTestOutput.example');
        

        $grammar = collect([
            new Click('click', '', "'Log in'", [], [], 'a'),
            new Click('click', '', "'Forgot your password?'", [], [], 'a'),
            new See('see', '', "'Mateus'", [], [], 'span'),
        ]);

        $processedText = (new FileEditor)->process($expectedInput, $grammar, 'testBasicExample');

        $this->assertEquals($expectedOutput, $processedText);
    }
}
