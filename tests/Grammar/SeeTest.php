<?php

namespace MagicTest\MagicTest\Tests\Grammar;

use MagicTest\MagicTest\Grammar\Grammar;
use MagicTest\MagicTest\Tests\TestCase;
use PhpParser\Node\Scalar\String_;

class SeeTest extends TestCase
{
    /** @test */
    public function it_properly_builds_a_see_grammar()
    {
        $fill = Grammar::for([
            'action' => 'see',
            'attributes' => [],
            'parent' => [],
            'tag' => 'span',
            'meta' => [
                'text' => "Some string that contains ' and '",
            ],
        ]);

        $this->assertEquals('assertSee', $fill->nameForParser());
        $this->assertEquals([
            new String_('Some string that contains \' and \''),
        ], $fill->arguments());
        $this->assertEquals(null, $fill->pause());
    }
}
