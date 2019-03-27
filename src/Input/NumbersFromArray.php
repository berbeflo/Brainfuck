<?php

declare(strict_types=1);
namespace berbeflo\Brainfuck\Input;

use InvalidArgumentException;
use RuntimeException;

class NumbersFromArray implements Input
{
    private $sourceArray;
    private $backupArray;

    public function __construct(array $numbersArray)
    {
        \array_walk($numbersArray, function ($number) {
            if (!\is_int($number)) {
                throw new InvalidArgumentException();
            }
        });

        $this->sourceArray = $numbersArray;
        $this->backupArray = $numbersArray;
    }

    public function reset() : void
    {
        $this->sourceArray = $this->backupArray;
    }

    public function getNextChar() : int
    {
        if (\count($this->sourceArray) === 0) {
            throw new RuntimeException();
        }

        return \array_shift($this->sourceArray);
    }
}
