<?php

declare(strict_types=1);
namespace berbeflo\Brainfuck\Test\Input;

use berbeflo\Brainfuck\Input\BytesFromString;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class BytesFromStringTest extends TestCase
{
    public function testGetBytes()
    {
        $bytesObject = new BytesFromString('asdf');
        $this->assertSame(\ord('a'), $bytesObject->getNextChar());
        $this->assertSame(\ord('s'), $bytesObject->getNextChar());
        $this->assertSame(\ord('d'), $bytesObject->getNextChar());
        $this->assertSame(\ord('f'), $bytesObject->getNextChar());
    }

    public function testException()
    {
        $bytesObject = new BytesFromString('');
        $this->expectException(RuntimeException::class);
        $bytesObject->getNextChar();
    }

    public function testReset()
    {
        $bytesObject = new BytesFromString('asd');
        $i = 3;

        while ($i-- > 0) {
            $this->assertSame(\ord('a'), $bytesObject->getNextChar());
            $this->assertSame(\ord('s'), $bytesObject->getNextChar());
            $this->assertSame(\ord('d'), $bytesObject->getNextChar());
            $bytesObject->reset();
        }
    }
}
