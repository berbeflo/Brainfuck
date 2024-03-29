<?php

declare(strict_types=1);

namespace berbeflo\Brainfuck\Input;

use berbeflo\Brainfuck\Definition\Input;
use berbeflo\Brainfuck\Definition\Resettable;
use InvalidArgumentException;
use RuntimeException;

class NumbersFromArray implements Input, Resettable
{
    /**
     * @var array<int, int>
     */
    private array $sourceArray;

    /**
     * @var array<int, int>
     */
    private array $backupArray;

    /**
     * @param array<int, int> $numbersArray
     */
    public function __construct(array $numbersArray)
    {
        \array_walk($numbersArray, function ($number) {
            if (!\is_int($number)) {
                throw new InvalidArgumentException('The input array must consist of integers');
            }
        });

        $this->sourceArray = $numbersArray;
        $this->backupArray = $numbersArray;
    }

    public function reset(): void
    {
        $this->sourceArray = $this->backupArray;
    }

    public function getNextChar(): int
    {
        if (\count($this->sourceArray) === 0) {
            throw new RuntimeException('Input Object cannot provide further data.');
        }

        return \array_shift($this->sourceArray);
    }
}
