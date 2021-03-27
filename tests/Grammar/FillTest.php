<?php

namespace MagicTest\MagicTest\Tests\Grammar;

use MagicTest\MagicTest\Grammar\Grammar;
use MagicTest\MagicTest\Grammar\Pause;
use MagicTest\MagicTest\Tests\TestCase;
use PhpParser\Node\Scalar\String_;

class FillTest extends TestCase
{
    /** @test */
    public function it_properly_builds_an_action()
    {
        $fill = Grammar::for([
            'action' => 'fill',
            'attributes' => [
                [
                    'name' => 'name',
                    'value' => 'email',
                    'isUnique' => true,
                ],
            ],
            'parent' => [],
            'tag' => 'input',
            'meta' => [
                'text' => 'myemail@gmail.com',
            ],
        ]);

        $this->assertEquals('type', $fill->nameForParser());
        $this->assertEquals([
            new String_('email'),
            new String_('myemail@gmail.com'),
        ], $fill->arguments());
        $this->assertEquals(null, $fill->pause());
    }

    /** @test */
    public function it_properly_adds_a_pause_to_a_livewire_input()
    {
        $fill = Grammar::for([
            'action' => 'fill',
            'attributes' => [
                [
                    'name' => 'name',
                    'value' => 'email',
                    'isUnique' => true,
                ],
                [
                    'name' => 'wire:model',
                    'value' => 'email',
                    'isUnique' => true,
                ],
            ],
            'parent' => [],
            'tag' => 'input',
            'meta' => [
                'text' => 'myemail@gmail.com',
            ],
        ]);

        $this->assertEquals('type', $fill->nameForParser());
        $this->assertEquals([
            new String_('email'),
            new String_('myemail@gmail.com'),
        ], $fill->arguments());
        $this->assertEquals(new Pause(200), $fill->pause());
    }

    /** @test */
    public function it_uses_the_livewire_selector_when_name_is_not_unique()
    {
        $fill = Grammar::for([
            'action' => 'fill',
            'attributes' => [
                [
                    'name' => 'name',
                    'value' => 'email',
                    'isUnique' => false,
                ],
                [
                    'name' => 'wire:model',
                    'value' => 'userEmail',
                    'isUnique' => true,
                ],
            ],
            'parent' => [],
            'tag' => 'input',
            'meta' => [
                'text' => 'myemail@gmail.com',
            ],
        ]);

        $this->assertEquals('type', $fill->nameForParser());
        $this->assertEquals([
            new String_('input[wire\:model=userEmail]'),
            new String_('myemail@gmail.com'),
        ], $fill->arguments());
        $this->assertEquals(new Pause(200), $fill->pause());
    }

    /** @test */
    public function it_gives_priority_to_the_name_attribute()
    {
        $fill = Grammar::for([
            'action' => 'fill',
            'attributes' => [
                [
                    'name' => 'id',
                    'value' => 'foo',
                    'isUnique' => true,
                ],
                [
                    'name' => 'name',
                    'value' => 'email',
                    'isUnique' => true,
                ],
                [
                    'name' => 'wire:model',
                    'value' => 'userEmail',
                    'isUnique' => true,
                ],
            ],
            'parent' => [],
            'tag' => 'input',
            'meta' => [
                'text' => 'myemail@gmail.com',
            ],
        ]);

        $this->assertEquals('type', $fill->nameForParser());
        $this->assertEquals([
            new String_('email'),
            new String_('myemail@gmail.com'),
        ], $fill->arguments());
        $this->assertEquals(new Pause(200), $fill->pause());
    }
}
