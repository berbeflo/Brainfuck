<?php

declare(strict_types=1);

namespace berbeflo\Brainfuck;

final class Interpreter
{
    private string $brainfuckCode;
    private Config $config;

    private int $pointer;
    private bool $isPrepared = false;

    /**
     * @var array<int, int>
     */
    private array $memory;

    /**
     * @var array<int, array>
     */
    private array $loopPositions;

    public function __construct(string $brainfuckCode, Config $config)
    {
        $this->brainfuckCode = $brainfuckCode;
        $this->config = $config;
    }

    public function prepare(): Interpreter
    {
        $this->loopPositions = [];

        for ($currentOperator = 0; $currentOperator < \strlen($this->brainfuckCode); $currentOperator++) {
            switch ($this->brainfuckCode[$currentOperator]) {
                case '[':
                    $this->loopPositions[] = [
                        'start' => $currentOperator,
                        'end' => 0,
                        'counter' => 0,
                    ];
                    break;
                case ']':
                    for ($index = \count($this->loopPositions) - 1; $index >= 0; $index--) {
                        if ($this->loopPositions[$index]['end'] === 0) {
                            $this->loopPositions[$index]['end'] = $currentOperator;
                            break;
                        }
                    }
                    break;
            }
        }

        $this->isPrepared = true;

        return $this;
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function execute(): Interpreter
    {
        if (!$this->isPrepared) {
            $this->prepare();
        }

        $defaultRegisterValue = null;
        $defaultPointerValue = null;

        ['register' => $defaultRegisterValue, 'pointer' => $defaultPointerValue] = $this->config->getDefaultValues();

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
                    $currentOperator = $this->findLoopStart($currentOperator) - 1;
                    break;
                default:
                    if ($this->config->getAllowUnknownTokens()) {
                        continue 2;
                    }
                    throw new InterpreterException(
                        'Token is not allowed.',
                        $currentOperator,
                        $this->brainfuckCode[$currentOperator]
                    );
            }
        }

        return $this;
    }

    private function readChar(): int
    {
        $char = $this->config->getInputObject()->getNextChar();

        if ($char > $this->config->getMaxRegisterValue() || $char < $this->config->getMinRegisterValue()) {
            throw new InterpreterException(str_replace('{0}', $char, 'The provided char <{0}> is out of bounds'));
        }

        return $char;
    }

    private function writeChar(int $char): void
    {
        $this->config->getOutputObject()->writeChar($char);
    }

    private function checkPointerValue(): void
    {
        if ($this->pointer > $this->config->getMaxPointerValue()) {
            if ($this->config->getWrapOnPointerOverflow()) {
                $this->pointer = $this->config->getMinPointerValue();

                return;
            }
            throw new InterpreterException(str_replace('{0}', $this->pointer, 'Cannot access register {0}'));
        }

        if ($this->pointer < $this->config->getMinPointerValue()) {
            if ($this->config->getWrapOnPointerOverflow()) {
                $this->pointer = $this->config->getMaxPointerValue();

                return;
            }
            throw new InterpreterException(str_replace('{0}', $this->pointer, 'Cannot access register {0}'));
        }
    }

    private function checkMemoryValue(): void
    {
        $currentValue = $this->memory[$this->pointer];

        if ($currentValue > $this->config->getMaxRegisterValue()) {
            if ($this->config->getWrapOnRegisterOverflow()) {
                $this->memory[$this->pointer] = $this->config->getMinRegisterValue();

                return;
            }
            throw new InterpreterException(str_replace('{0}', $currentValue, 'Cannot handle value {0}'));
        }

        if ($currentValue < $this->config->getMinRegisterValue()) {
            if ($this->config->getWrapOnRegisterOverflow()) {
                $this->memory[$this->pointer] = $this->config->getMaxRegisterValue();

                return;
            }
            throw new InterpreterException(str_replace('{0}', $currentValue, 'Cannot handle value {0}'));
        }
    }

    private function isLoopConditionSatisfied(): bool
    {
        $falseValue = null;
        ['register' => $falseValue] = $this->config->getDefaultValues();

        return $this->memory[$this->pointer] > $falseValue;
    }

    private function findLoopEnd(int $position): int
    {
        $endPosition = $this->searchInLoop('start', 'end', $position);

        if ($endPosition === 0) {
            throw new InterpreterException('Unmatched loops in code.');
        }

        return $endPosition;
    }

    private function findLoopStart(int $position): int
    {
        return $this->searchInLoop('end', 'start', $position);
    }

    private function searchInLoop(string $keyNeedle, string $keyGet, int $position): int
    {
        foreach ($this->loopPositions as $loopPosition) {
            if ($loopPosition[$keyNeedle] === $position) {
                return $loopPosition[$keyGet];
            }
        }

        throw new InterpreterException('Unmatched loops in code.');
    }

    private function checkIterations(int $position): void
    {
        foreach ($this->loopPositions as $key => $loopPosition) {
            if ($loopPosition['start'] === $position) {
                $this->loopPositions[$key]['counter'] += 1;

                if ($this->loopPositions[$key]['counter'] > $this->config->getMaximumIterations()) {
                    throw new InterpreterException('Maximum iterations exceeded.');
                }
            }
        }
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    private function resetLoopCounter(): void
    {
        foreach ($this->loopPositions as $key => $loopPosition) {
            $this->loopPositions[$key][2] = 0;
        }
    }
}
