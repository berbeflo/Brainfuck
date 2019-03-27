<?php

declare(strict_types=1);
namespace berbeflo\Brainfuck\Test\Input;

use berbeflo\Brainfuck\Input\NumbersFromArray;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class NumbersFromArrayTest extends TestCase
{
    public function testGetNumbers()
    {
        $numbersObject = new NumbersFromArray([2, 4, 8]);
        $this->assertSame(2, $numbersObject->getNextChar());
        $this->assertSame(4, $numbersObject->getNextChar());
        $this->assertSame(8, $numbersObject->getNextChar());
    }

    public function testException()
    {
        $numbersObject = new NumbersFromArray([]);
        $this->expectException(RuntimeException::class);
        $numbersObject->getNextChar();
    }

    public function testReset()
    {
        $numbersObject = new NumbersFromArray([2, 4]);
        $i = 3;

        while ($i-- > 0) {
            $this->assertSame(2, $numbersObject->getNextChar());
            $this->assertSame(4, $numbersObject->getNextChar());
            $numbersObject->reset();
        }
    }

    public function testNoIntException()
    {
        $this->expectException(InvalidArgumentException::class);
        $numbersObject = new NumbersFromArray(["1"]);
    }
}
