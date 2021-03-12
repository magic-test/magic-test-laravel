<?php

namespace MagicTest\MagicTest\Tests\Parser;

use MagicTest\MagicTest\Exceptions\InvalidFileException;
use MagicTest\MagicTest\Parser\PhpFile;
use MagicTest\MagicTest\Tests\TestCase;

class PhpFileTest extends TestCase
{
    /** @test */
    public function it_validates_a_class_missing_a_method()
    {
        $this->expectException(InvalidFileException::class);

        $fixture = file_get_contents(__DIR__ . './../fixtures/Errors/MissingMethod.php');

        new PhpFile($fixture, 'testBasicExample');
    }

    /** @test */
    public function it_validates_a_class_missing_the_method_Call()
    {
        $this->expectException(InvalidFileException::class);

        $fixture = file_get_contents(__DIR__ . './../fixtures/Errors/MissingMethodCall.php');

        new PhpFile($fixture, 'testBasicExample');
    }

    /** @test */
    public function it_validates_a_class_missing_the_closure()
    {
        $this->expectException(InvalidFileException::class);

        $fixture = file_get_contents(__DIR__ . './../fixtures/Errors/MissingClosure.php');

        new PhpFile($fixture, 'testBasicExample');
    }
}
