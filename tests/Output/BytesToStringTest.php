<?php

declare(strict_types=1);

namespace berbeflo\Brainfuck\Test\Output;

use berbeflo\Brainfuck\Output\BytesToString;
use PHPUnit\Framework\TestCase;

class BytesToStringTest extends TestCase
{
    public function testWriteChars()
    {
        $bytesObject = new BytesToString();
        $bytesObject->writeChar(\ord('a'));
        $bytesObject->writeChar(\ord('b'));
        $bytesObject->writeChar(\ord('c'));
        $this->assertSame('abc', $bytesObject->getResult());
    }

    public function testReset()
    {
        $bytesObject = new BytesToString();
        $i = 3;

        while ($i-- > 0) {
            $bytesObject->writeChar(\ord('a'));
            $bytesObject->writeChar(\ord('b'));
            $this->assertSame('ab', $bytesObject->getResult());
            $this->assertSame('ab', $bytesObject->getResult());
            $bytesObject->reset();
        }
    }

    public function testEmptyResult()
    {
        $bytesObject = new BytesToString();
        $this->assertSame('', $bytesObject->getResult());
    }
}
