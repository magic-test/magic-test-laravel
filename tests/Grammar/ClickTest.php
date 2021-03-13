<?php

namespace MagicTest\MagicTest\Tests\Grammar;

use MagicTest\MagicTest\Grammar\Grammar;
use MagicTest\MagicTest\Grammar\Pause;
use MagicTest\MagicTest\Tests\TestCase;
use PhpParser\Node\Scalar\String_;

class ClickTest extends TestCase
{
    /** @test */
    public function it_properly_builds_a_select()
    {
        $array = [
            "action" => "click",
            "attributes" => [
               [
                  "name" => "id",
                  "value" => "location",
                  "isUnique" => true,
              ],
               [
                  "name" => "name",
                  "value" => "country",
                  "isUnique" => true,
              ],
               [
                  "name" => "class",
                  "value" => "mt-1",
                  "isUnique" => true,
              ],
            ],
            "parent" => [
               
            ],
            "tag" => "select",
            "meta" => [
               "type" => "select-one",
               "label" => "EU",
            ],
        ];

        $click = Grammar::for($array);


        $this->assertEquals('select', $click->nameForParser());
        $this->assertEquals([
                new String_('country'),
                new String_('EU'),
        ], $click->arguments());
        $this->assertEquals(new Pause(500), $click->pause());
    }

    /** @test */
    public function it_properly_builds_a_radio()
    {
        $array = [
            "action" => "click",
            "attributes" => [
               [
                  "name" => "id",
                  "value" => "push_everything",
                  "isUnique" => true,
               ],
               [
                  "name" => "name",
                  "value" => "some_radio",
                  "isUnique" => false,
               ],
               [
                  "name" => "type",
                  "value" => "radio",
                  "isUnique" => false,
               ],
               [
                  "name" => "class",
                  "value" => "focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300",
                  "isUnique" => false,
               ],
            ],
            "parent" => [
               
            ],
            "tag" => "input",
            "meta" => [
               "type" => "radio",
               "label" => "Option 1",
            ],
        ];

        $click = Grammar::for($array);


        $this->assertEquals('radio', $click->nameForParser());
        $this->assertEquals([
                new String_('input[name=some_radio]'),
                new String_('Option 1'),
        ], $click->arguments());
        $this->assertEquals(new Pause(500), $click->pause());
    }
}
