<?php

declare(strict_types=1);

namespace berbeflo\Brainfuck\Test\Output;

use berbeflo\Brainfuck\Output\NumbersToArray;
use PHPUnit\Framework\TestCase;

class NumbersToArrayTest extends TestCase
{
    public function testWriteNumbers()
    {
        $numbersObject = new NumbersToArray();
        $numbersObject->writeChar(4);
        $numbersObject->writeChar(8);
        $this->assertSame([4, 8], $numbersObject->getResult());
    }

    public function testReset()
    {
        $numbersObject = new NumbersToArray();
        $i = 3;

        while ($i-- > 0) {
            $numbersObject->writeChar(2);
            $numbersObject->writeChar(4);
            $this->assertSame([2, 4], $numbersObject->getResult());
            $this->assertSame([2, 4], $numbersObject->getResult());
            $numbersObject->reset();
        }
    }

    public function testEmptyResult()
    {
        $numbersObject = new NumbersToArray();
        $this->assertSame([], $numbersObject->getResult());
    }
}
