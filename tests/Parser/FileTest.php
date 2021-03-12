<?php

namespace MagicTest\MagicTest\Tests\Parser;

use MagicTest\MagicTest\Exceptions\InvalidFileException;
use MagicTest\MagicTest\Parser\File;
use MagicTest\MagicTest\Tests\TestCase;

class FileTest extends TestCase
{
    /** @test */
    public function it_validates_a_class_missing_a_method()
    {
        $this->expectException(InvalidFileException::class);

        $fixture = file_get_contents(__DIR__ . './../fixtures/Errors/MissingMethod.php');

        new File($fixture, 'testBasicExample');
    }

    /** @test */
    public function it_validates_a_class_missing_the_method_Call()
    {
        $this->expectException(InvalidFileException::class);

        $fixture = file_get_contents(__DIR__ . './../fixtures/Errors/MissingMethodCall.php');

        new File($fixture, 'testBasicExample');
    }

    /** @test */
    public function it_validates_a_class_missing_the_closure()
    {
        $this->expectException(InvalidFileException::class);

        $fixture = file_get_contents(__DIR__ . './../fixtures/Errors/MissingClosure.php');

        new File($fixture, 'testBasicExample');
    }
}
