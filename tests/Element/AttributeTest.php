<?php

namespace MagicTest\MagicTest\Tests\Element;

use MagicTest\MagicTest\Element\Attribute;
use MagicTest\MagicTest\Tests\TestCase;

class ElementTest extends TestCase
{
    /** @test */
    public function it_parses_a_livewire_name_field()
    {
        $attribute = new Attribute('wire:model', 'name');

        $this->assertEquals('input[wire\\:model=name]', $attribute->buildSelector());
    }
}
