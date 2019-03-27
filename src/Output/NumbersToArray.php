<?php

declare(strict_types=1);
namespace berbeflo\Brainfuck\Output;

class NumbersToArray implements Output
{
    private $currentArray;

    public function __construct()
    {
        $this->currentArray = [];
    }

    public function writeChar(int $char) : void
    {
        $this->currentArray[] = $char;
    }

    public function getResult() : array
    {
        return $this->currentArray;
    }

    public function reset() : void
    {
        $this->currentArray = [];
    }
}
