<?php

declare(strict_types=1);
namespace berbeflo\Brainfuck\Output;

use berbeflo\Brainfuck\Definition\Output;
use berbeflo\Brainfuck\Definition\Resettable;

class NumbersToArray implements Output, Resettable
{
    /**
     * @var array<int, int>
     */
    private array $currentArray;

    public function __construct()
    {
        $this->currentArray = [];
    }

    public function writeChar(int $char) : void
    {
        $this->currentArray[] = $char;
    }

    /**
     * @return array<int, int>
     */
    public function getResult() : array
    {
        return $this->currentArray;
    }

    public function reset() : void
    {
        $this->currentArray = [];
    }
}
