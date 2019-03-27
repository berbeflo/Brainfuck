<?php

declare(strict_types=1);
namespace berbeflo\Brainfuck;

use RuntimeException;

final class Interpreter
{
    private $brainfuckCode;
    private $config;

    private $pointer;
    private $memory;

    private $loopPositions;

    public function __construct(string $brainfuckCode, Config $config)
    {
        $this->brainfuckCode = $brainfuckCode;
        $this->config = $config;
    }

    public function prepare() : Interpreter
    {
        $this->loopPositions = [];

        for ($currentOperator = 0; $currentOperator < \strlen($this->brainfuckCode); $currentOperator++) {
            switch ($this->brainfuckCode[$currentOperator]) {
                case '[':
                    $this->loopPositions[] = [$currentOperator, 0, 0];
                    break;
                case ']':
                    for ($index = \count($this->loopPositions)-1; $index >= 0; $index--) {
                        if ($this->loopPositions[$index][1] === 0) {
                            $this->loopPositions[$index][1] = $currentOperator;
                            break;
                        }
                    }
                    break;
            }
        }

        return $this;
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function execute() : Interpreter
    {
        ['register' => $defaultRegisterValue, 'pointer' => $defaultPointerValue] = $this->getDefaultValues();

        $this->pointer = $defaultPointerValue;
        $this->memory = [];
        $this->memory[$this->pointer] = $defaultRegisterValue;
        $this->resetLoopCounter();

        for ($currentOperator = 0; $currentOperator < \strlen($this->brainfuckCode); $currentOperator++) {
            switch ($this->brainfuckCode[$currentOperator]) {
                case ',':
                    $this->memory[$this->pointer] = $this->readChar();
                    break;
                case '.':
                    $this->writeChar($this->memory[$this->pointer]);
                    break;
                case '+':
                    $this->memory[$this->pointer]++;
                    $this->checkMemoryValue();
                    break;
                case '-':
                    $this->memory[$this->pointer]--;
                    $this->checkMemoryValue();
                    break;
                case '>':
                    $this->pointer++;
                    $this->checkPointerValue();

                    if (!\array_key_exists($this->pointer, $this->memory)) {
                        $this->memory[$this->pointer] = $defaultRegisterValue;
                    }
                    break;
                case '<':
                    $this->pointer--;
                    $this->checkPointerValue();

                    if (!\array_key_exists($this->pointer, $this->memory)) {
                        $this->memory[$this->pointer] = $defaultRegisterValue;
                    }
                    break;
                case '[':
                    if (!$this->isLoopConditionSatisfied()) {
                        $currentOperator = $this->findLoopEnd($currentOperator);
                        break;
                    }
                    $this->checkIterations($currentOperator);
                    break;
                case ']':
                    $currentOperator = $this->findLoopStart($currentOperator)-1;
                    break;
                default:
                    throw new RuntimeException();
            }
        }

        return $this;
    }

    private function getDefaultValues() : array
    {
        $defaultRegisterValue =
            \min(
                $this->config->getMaxRegisterValue(),
                \max($this->config->getMinRegisterValue(), 0)
            );
        $defaultPointerValue = \min(
            $this->config->getMaxPointerValue(),
            \max($this->config->getMinPointerValue(), 0)
        );

        return ['register' => $defaultRegisterValue, 'pointer' => $defaultPointerValue];
    }

    private function readChar() : int
    {
        $char = $this->config->getInputObject()->getNextChar();

        if ($char > $this->config->getMaxRegisterValue() || $char < $this->config->getMinRegisterValue()) {
            throw new RuntimeException();
        }

        return $char;
    }

    private function writeChar(int $char) : void
    {
        $this->config->getOutputObject()->writeChar($char);
    }

    private function checkPointerValue() : void
    {
        if ($this->pointer > $this->config->getMaxPointerValue()) {
            if ($this->config->getWrapOnPointerOverflow()) {
                $this->pointer = $this->config->getMinPointerValue();

                return;
            }
            throw new RuntimeException();
        }

        if ($this->pointer < $this->config->getMinPointerValue()) {
            if ($this->config->getWrapOnPointerOverflow()) {
                $this->pointer = $this->config->getMaxPointerValue();

                return;
            }
            throw new RuntimeException();
        }
    }

    private function checkMemoryValue() : void
    {
        $currentValue = $this->memory[$this->pointer];

        if ($currentValue > $this->config->getMaxRegisterValue()) {
            if ($this->config->getWrapOnRegisterOverflow()) {
                $this->memory[$this->pointer] = $this->config->getMinRegisterValue();

                return;
            }
            throw new RuntimeException();
        }

        if ($currentValue < $this->config->getMinRegisterValue()) {
            if ($this->config->getWrapOnRegisterOverflow()) {
                $this->memory[$this->pointer] = $this->config->getMaxRegisterValue();

                return;
            }
            throw new RuntimeException();
        }
    }

    private function isLoopConditionSatisfied() : bool
    {
        ['register' => $falseValue] = $this->getDefaultValues();

        return $this->memory[$this->pointer] > $falseValue;
    }

    private function findLoopEnd(int $position) : int
    {
        foreach ($this->loopPositions as $loopPosition) {
            if ($loopPosition[0] === $position) {
                if ($loopPosition[1] === 0) {
                    throw new RuntimeException();
                }

                return $loopPosition[1];
            }
        }
    }

    private function findLoopStart(int $position) : int
    {
        foreach ($this->loopPositions as $loopPosition) {
            if ($loopPosition[1] === $position) {
                return $loopPosition[0];
            }
        }
        throw new RuntimeException();
    }

    private function checkIterations(int $position) : void
    {
        foreach ($this->loopPositions as $key => $loopPosition) {
            if ($loopPosition[0] === $position) {
                $this->loopPositions[$key][2] += 1;

                if ($this->loopPositions[$key][2] > $this->config->getMaximumIterations()) {
                    throw new RuntimeException();
                }
            }
        }
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    private function resetLoopCounter() : void
    {
        foreach ($this->loopPositions as $key => $loopPosition) {
            $this->loopPositions[$key][2] = 0;
        }
    }
}
