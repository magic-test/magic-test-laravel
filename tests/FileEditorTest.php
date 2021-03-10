<?php

namespace MagicTest\MagicTest\Tests;

use MagicTest\MagicTest\FileEditor;
use MagicTest\MagicTest\Grammar\Click;
use MagicTest\MagicTest\Grammar\Fill;
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
    public function it_properly_parses_a_file_that_uses_the_magic_macro()
    {
        $expectedInput = file_get_contents(__DIR__ . '/fixtures/ExampleTestWithContentMacro.example');
        $expectedOutput = file_get_contents(__DIR__ . '/fixtures/ExampleTestWithContentMacroOutput.example');
        

        $grammar = collect([
            new Click('', "'Forgot your password?'", [], [], 'a'),
            new See('', "'Mateus'", [], [], 'span'),
        ]);

        $processedText = (new FileEditor)->process($expectedInput, $grammar, 'testBasicExample');

        $this->assertEquals($expectedOutput, $processedText);
    }

    /** @test */
    public function it_properly_adds_new_actions_to_a_test()
    {
        $input = file_get_contents(__DIR__ . '/fixtures/ExampleTestWithMacroFinishedInput.example');
        $expectedOutput = file_get_contents(__DIR__ . '/fixtures/ExampleTestWithMacroFinishedOutput.example');
        

        $grammar = collect([
            new Click('', "'Mateus'", [], [], 'button'),
        ]);

        $processedText = (new FileEditor)->process($input, $grammar, 'testBasicExample');
        $this->assertEquals($expectedOutput, $processedText);
    }

    /** @test */
    public function it_properly_adds_fills_to_a_livewire_test()
    {
        $input = file_get_contents(__DIR__ . '/fixtures/ExampleTestWithContentAndLivewireInput.example');
        $expectedOutput = file_get_contents(__DIR__ . '/fixtures/ExampleTestWithContentAndLivewireOutput.example');

        $grammar = collect([
            new Fill('', 'name', [
                'text' => "'Mateus'",
            ], [], 'button', [
                'isLivewire' => true,
            ]),
            new Fill('', 'email', [
                'text' => "'mateus@mateusguimaraes.com'",
            ], [], 'button', [
                'isLivewire' => true,
            ]),
        ]);

        $processedText = (new FileEditor)->process($input, $grammar, 'testBasicExample');
        $this->assertEquals($expectedOutput, $processedText);
    }

    /** @test */
    public function it_finishes_a_test_using_the_macro()
    {
        $expectedInput = file_get_contents(__DIR__ . '/fixtures/ExampleTestWithContentMacroOutput.example');
        $expectedOutput = file_get_contents(__DIR__ . '/fixtures/ExampleTestWithContentMacroFinishedOutput.example');
        
        $processedText = (new FileEditor)->finish($expectedInput, 'testBasicExample');

        $this->assertEquals($expectedOutput, $processedText);
    }
}
