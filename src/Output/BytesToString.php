<?php

declare(strict_types=1);
namespace berbeflo\Brainfuck\Output;

class BytesToString implements Output
{
    private $currentString;

    public function __construct()
    {
        $this->currentString = '';
    }

    public function getResult() : string
    {
        return $this->currentString;
    }

    public function writeChar(int $char) : void
    {
        $this->currentString .= \chr($char);
    }

    public function reset() : void
    {
        $this->currentString = '';
    }
}
