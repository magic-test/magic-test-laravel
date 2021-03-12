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
        $expectedInput = file_get_contents(__DIR__ . '/fixtures/Regular/input.php');
        $expectedOutput = file_get_contents(__DIR__ . '/fixtures/Regular/output.php');
        

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
        $expectedInput = file_get_contents(__DIR__ . '/fixtures/WithActions/input.php');
        $expectedOutput = file_get_contents(__DIR__ . '/fixtures/WithActions/output.php');
        

        $grammar = collect([
            new Click('', "'Forgot your password?'", [], [], 'a'),
            new See('', "'Mateus'", [], [], 'span'),
        ]);

        $processedText = (new FileEditor)->process($expectedInput, $grammar, 'testBasicExample');

        $this->assertEquals($expectedOutput, $processedText);
    }

    /** @test */
    public function it_properly_adds_fills_to_a_livewire_test()
    {
        $input = file_get_contents(__DIR__ . '/fixtures/Livewire/input.php');
        $expectedOutput = file_get_contents(__DIR__ . '/fixtures/Livewire/output.php');

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
        $expectedInput = file_get_contents(__DIR__ . '/fixtures/Finished/input.php');
        $expectedOutput = file_get_contents(__DIR__ . '/fixtures/Finished/output.php');
        
        $processedText = (new FileEditor)->finish($expectedInput, 'testBasicExample');

        $this->assertEquals($expectedOutput, $processedText);
    }

    /** @test */
    public function it_properly_adds_methods_to_a_file_using_inline_code()
    {
        $expectedInput = file_get_contents(__DIR__ . '/fixtures/WithActionsAndInlineCode/input.php');
        $expectedOutput = file_get_contents(__DIR__ . '/fixtures/WithActionsAndInlineCode/output.php');
        

        $grammar = collect([
            new Click('', "'Forgot your password?'", [], [], 'a'),
            new See('', "'Mateus'", [], [], 'span'),
        ]);

        $processedText = (new FileEditor)->process($expectedInput, $grammar, 'testBasicExample');

        $this->assertEquals($expectedOutput, $processedText);
    }
}
