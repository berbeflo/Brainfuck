<?php

declare(strict_types=1);

namespace berbeflo\Brainfuck\Test;

use berbeflo\Brainfuck\Config;
use berbeflo\Brainfuck\Input\NumbersFromArray;
use berbeflo\Brainfuck\Interpreter;
use berbeflo\Brainfuck\Output\NumbersToArray;
use PHPUnit\Framework\TestCase;
use RuntimeException;

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

    public function testInputOutput()
    {
        $bfCode = ',.';
        $output = new NumbersToArray();
        $config = new Config();
        $config
            ->setInputObject(new NumbersFromArray([42]))
            ->setOutputObject($output);
        $interpreter = new Interpreter($bfCode, $config);
        $interpreter
            ->prepare()
            ->execute();
        $this->assertSame([42], $output->getResult());
    }

    public function testPointerOverflowException()
    {
        $config = new Config();
        $config->setMaxPointerValue(2);
        $bfWorkingCode = '>>';
        $bfNotWorkingCode = '>>>';
        $interpreter = new Interpreter($bfWorkingCode, $config);
        $interpreter->prepare()->execute();
        $interpreter = new Interpreter($bfNotWorkingCode, $config);
        $interpreter->prepare();
        $this->expectException(RuntimeException::class);
        $interpreter->execute();
    }

    public function testPointerUnderflowException()
    {
        $config = new Config();
        $config->setMinPointerValue(2);
        $bfWorkingCode = '><';
        $bfNotWorkingCode = '><<';
        $interpreter = new Interpreter($bfWorkingCode, $config);
        $interpreter->prepare()->execute();
        $interpreter = new Interpreter($bfNotWorkingCode, $config);
        $interpreter->prepare();
        $this->expectException(RuntimeException::class);
        $interpreter->execute();
    }

    public function testPointerOverflowWrap()
    {
        $config = new Config();
        $input = new NumbersFromArray([12]);
        $output = new NumbersToArray();
        $config
            ->setInputObject($input)
            ->setOutputObject($output)
            ->setMaxPointerValue(2)
            ->setWrapOnPointerOverflow(true);
        $bfCode = ',>>>.';
        $interpreter = new Interpreter($bfCode, $config);
        $interpreter->prepare()->execute();
        $this->assertSame([12], $output->getResult());
    }

    public function testPointerUnderflowWrap()
    {
        $config = new Config();
        $input = new NumbersFromArray([12]);
        $output = new NumbersToArray();
        $config
            ->setInputObject($input)
            ->setOutputObject($output)
            ->setMinPointerValue(2)
            ->setMaxPointerValue(4)
            ->setWrapOnPointerOverflow(true);
        $bfCode = '>,<<<.';
        $interpreter = new Interpreter($bfCode, $config);
        $interpreter->prepare()->execute();
        $this->assertSame([12], $output->getResult());
    }

    public function testRegisterOverflowException()
    {
        $config = new Config();
        $config->setMaxRegisterValue(2);
        $bfWorkingCode = '++';
        $bfNotWorkingCode = '+++';
        $interpreter = new Interpreter($bfWorkingCode, $config);
        $interpreter->prepare()->execute();
        $interpreter = new Interpreter($bfNotWorkingCode, $config);
        $interpreter->prepare();
        $this->expectException(RuntimeException::class);
        $interpreter->execute();
    }

    public function testRegisterUnderflowException()
    {
        $config = new Config();
        $config->setMinRegisterValue(-2);
        $bfWorkingCode = '--';
        $bfNotWorkingCode = '---';
        $interpreter = new Interpreter($bfWorkingCode, $config);
        $interpreter->prepare()->execute();
        $interpreter = new Interpreter($bfNotWorkingCode, $config);
        $interpreter->prepare();
        $this->expectException(RuntimeException::class);
        $interpreter->execute();
    }

    public function testRegisterWrap()
    {
        $input = new NumbersFromArray([2]);
        $output = new NumbersToArray();
        $config = new Config();
        $config
            ->setMaxRegisterValue(2)
            ->setMinRegisterValue(-2)
            ->setWrapOnRegisterOverflow(true)
            ->setInputObject($input)
            ->setOutputObject($output);
        $bfCode = ',+.-.';
        $interpreter = new Interpreter($bfCode, $config);
        $interpreter->prepare()->execute();
        $this->assertSame([-2,2], $output->getResult());
    }

    public function testInputTooSmallException()
    {
        $input = new NumbersFromArray([2]);
        $bfCode = ',';
        $config = new Config();
        $config
            ->setInputObject($input)
            ->setMinRegisterValue(3);
        $interpreter = new Interpreter($bfCode, $config);
        $interpreter->prepare();
        $this->expectException(RuntimeException::class);
        $interpreter->execute();
    }

    public function testInputTooBigException()
    {
        $input = new NumbersFromArray([2]);
        $bfCode = ',';
        $config = new Config();
        $config
            ->setInputObject($input)
            ->setMaxRegisterValue(1);
        $interpreter = new Interpreter($bfCode, $config);
        $interpreter->prepare();
        $this->expectException(RuntimeException::class);
        $interpreter->execute();
    }

    public function testUnknownOperatorException()
    {
        $bfCode = '/';
        $config = new Config();
        $config->setAllowUnknownTokens(false);
        $interpreter = new Interpreter($bfCode, $config);
        $interpreter->prepare();
        $this->expectException(RuntimeException::class);
        $interpreter->execute();
    }

    public function testAllowUnknownToken()
    {
        $bfCode = ',/-/.';
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

    public function testSimpleLoop()
    {
        $bfCode = ',>,<[->+<]>.';
        $input = new NumbersFromArray([2, 3]);
        $output = new NumbersToArray();
        $config = new Config();
        $config
            ->setInputObject($input)
            ->setOutputObject($output);
        $interpreter = new Interpreter($bfCode, $config);
        $interpreter->prepare()->execute();
        $this->assertSame([5], $output->getResult());
    }

    public function testExecuteWithoutPrepare()
    {
        $bfCode = ',>,<[->+<]>.';
        $input = new NumbersFromArray([2, 3]);
        $output = new NumbersToArray();
        $config = new Config();
        $config
            ->setInputObject($input)
            ->setOutputObject($output);
        $interpreter = new Interpreter($bfCode, $config);
        $interpreter->execute();
        $this->assertSame([5], $output->getResult());
    }

    public function testTwoLoops()
    {
        $bfCode = ',>,<[->+<]>[-<+>]<.';
        $input = new NumbersFromArray([2, 3]);
        $output = new NumbersToArray();
        $config = new Config();
        $config
            ->setInputObject($input)
            ->setOutputObject($output);
        $interpreter = new Interpreter($bfCode, $config);
        $interpreter->prepare()->execute();
        $this->assertSame([5], $output->getResult());
    }

    public function testNestedLoops()
    {
        $bfCode = ',>,>,<<[->[->+<]<]>>.';
        $input = new NumbersFromArray([2, 3, 4]);
        $output = new NumbersToArray();
        $config = new Config();
        $config
            ->setInputObject($input)
            ->setOutputObject($output);
        $interpreter = new Interpreter($bfCode, $config);
        $interpreter->prepare()->execute();
        $this->assertSame([7], $output->getResult());
    }

    public function testNonZeroLoopBoundary()
    {
        $bfCode = ',>,<[->+<]>.';
        $input = new NumbersFromArray([2, 3]);
        $output = new NumbersToArray();
        $config = new Config();
        $config
            ->setInputObject($input)
            ->setOutputObject($output)
            ->setMinRegisterValue(1);
        $interpreter = new Interpreter($bfCode, $config);
        $interpreter->prepare()->execute();
        $this->assertSame([4], $output->getResult());
    }

    public function testMissingLoopStart()
    {
        $bfCode = ']';
        $config = new Config();
        $interpreter = new Interpreter($bfCode, $config);
        $interpreter->prepare();
        $this->expectException(RuntimeException::class);
        $interpreter->execute();
    }

    public function testMissingLoopEnd()
    {
        $bfCode = '[';
        $config = new Config();
        $interpreter = new Interpreter($bfCode, $config);
        $interpreter->prepare();
        $this->expectException(RuntimeException::class);
        $interpreter->execute();
    }

    public function testMaximumInterations()
    {
        $bfCode = ',[-]';
        $input = new NumbersFromArray([5]);
        $config = new Config();
        $config
            ->setMaximumIterations(5)
            ->setInputObject($input);
        $interpreter = new Interpreter($bfCode, $config);
        $interpreter->prepare()->execute();
        $config->setMaximumIterations(4);
        $input->reset();
        $this->expectException(RuntimeException::class);
        $interpreter->execute();
    }
}
