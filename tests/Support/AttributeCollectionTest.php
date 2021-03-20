<?php

namespace MagicTest\MagicTest\Tests\Support;

use MagicTest\MagicTest\Element\Attribute;
use MagicTest\MagicTest\Support\AttributeCollection;
use MagicTest\MagicTest\Tests\TestCase;

class AttributeCollectionTest extends TestCase
{
    /** @test */
    public function it_reorders_attributes_including_a_name()
    {
        $collection = (new AttributeCollection([
            [
                'name' => 'id',
                'value' => 'foo',
            ],
            [
                'name' => 'wire:model',
                'value' => 'bar',
            ],
            [
                'name' => 'name',
                'value' => 'baz',
            ],
        ]))->map(fn ($element) => new Attribute($element['name'], $element['value']));

        $this->assertEquals(['name', 'id', 'wire:model'], $collection->reorderItems()->pluck('name')->toArray());
    }

    /** @test */
    public function it_reorders_items_and_gives_preference_to_dusk()
    {
        $collection = (new AttributeCollection([
            [
                'name' => 'id',
                'value' => 'foo',
            ],
            [
                'name' => 'wire:model',
                'value' => 'bar',
            ],
            [
                'name' => 'dusk',
                'value' => 'biz',
            ],
            [
                'name' => 'name',
                'value' => 'baz',
            ],
        ]))->map(fn ($element) => new Attribute($element['name'], $element['value']));


        $this->assertEquals(['dusk', 'name', 'id', 'wire:model'], $collection->reorderItems()->pluck('name')->toArray());
    }
}
