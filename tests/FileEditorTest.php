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
            new Click([], [], 'a', ['text' => 'Log in']),
            new Click([], [], 'a', ['text' => 'Forgot your password?']),
            new See([], [], 'span', ['text' => 'Mateus']),
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
            new Click([], [], 'a', ['text' => 'Forgot your password?']),
            new See([], [], 'span', ['text' => 'Mateus']),
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
            new Fill([
                ['name' => 'name', 'value' => 'name'],
                ['name' => 'wire:model', 'value' => 'name'],
            ], [], 'input', [
                'text' => 'Mateus',
            ]),
            new Fill([
                ['name' => 'name', 'value' => 'email'],
                ['name' => 'wire:model', 'email'],
            ], [], 'input', [
                'text' => 'mateus@mateusguimaraes.com',
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
            new Click([], [], 'a', ['text' => 'Forgot your password?']),
            new See([], [], 'span', ['text' => 'Mateus']),
        ]);

        $processedText = (new FileEditor)->process($expectedInput, $grammar, 'testBasicExample');

        $this->assertEquals($expectedOutput, $processedText);
    }

    /** @test */
    public function it_properly_adds_content_to_a_file_with_two_closures()
    {
        $expectedInput = file_get_contents(__DIR__ . '/fixtures/WithActionsAndTwoClosures/input.php');
        $expectedOutput = file_get_contents(__DIR__ . '/fixtures/WithActionsAndTwoClosures/output.php');
        

        $grammar = collect([
            new Click([], [], 'a', ['text' => 'Forgot your password?']),
            new See([], [], 'span', ['text' => 'Mateus']),
        ]);

        $processedText = (new FileEditor)->process($expectedInput, $grammar, 'testBasicExample');

        $this->assertEquals($expectedOutput, $processedText);
    }
}
