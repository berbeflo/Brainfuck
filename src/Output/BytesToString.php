<?php

declare(strict_types=1);
namespace berbeflo\Brainfuck\Output;

use berbeflo\Brainfuck\Definition\Output;
use berbeflo\Brainfuck\Definition\Resettable;

class BytesToString implements Output, Resettable
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
