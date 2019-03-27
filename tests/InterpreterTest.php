<?php

declare(strict_types=1);
namespace berbeflo\Brainfuck\Test;

use berbeflo\Brainfuck\Config;
use berbeflo\Brainfuck\Input\NumbersFromArray;
use berbeflo\Brainfuck\Interpreter;
use berbeflo\Brainfuck\Output\NumbersToArray;
use PHPUnit\Framework\TestCase;

class InterpreterTest extends TestCase
{
    public function testIncrement()
    {
        $bfCode = ',+.';
        $output = new NumbersToArray();
        $config = new Config();
        $config
            ->setInputObject(new NumbersFromArray([5]))
            ->setOutputObject($output);
        $interpreter = new Interpreter($bfCode, $config);
        $interpreter
            ->prepare()
            ->execute();
        $this->assertSame([6], $output->getResult());
    }

    public function testDecrement()
    {
        $bfCode = ',-.';
        $output = new NumbersToArray();
        $config = new Config();
        $config
            ->setInputObject(new NumbersFromArray([5]))
            ->setOutputObject($output);
        $interpreter = new Interpreter($bfCode, $config);
        $interpreter
            ->prepare()
            ->execute();
        $this->assertSame([4], $output->getResult());
    }

    public function testPointer()
    {
        $bfCode = ',>,>,.<.<.';
        $output = new NumbersToArray();
        $config = new Config();
        $config
            ->setInputObject(new NumbersFromArray([1, 2, 3]))
            ->setOutputObject($output);
        $interpreter = new Interpreter($bfCode, $config);
        $interpreter
            ->prepare()
            ->execute();
        $this->assertSame([3, 2, 1], $output->getResult());
    }
}
