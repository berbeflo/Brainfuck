<?php

declare(strict_types=1);
namespace berbeflo\Brainfuck\Input;

use berbeflo\Brainfuck\Definition\Input;
use berbeflo\Brainfuck\Definition\Resettable;
use RuntimeException;

class BytesFromString implements Input, Resettable
{
    private string $sourceString;
    private string $backupString;

    public function __construct(string $byteString)
    {
        $this->sourceString = $byteString;
        $this->backupString = $byteString;
    }

    public function reset() : void
    {
        $this->sourceString = $this->backupString;
    }

    public function getNextChar() : int
    {
        if (\strlen($this->sourceString) === 0) {
            throw new RuntimeException();
        }
        $byte = \ord(\substr($this->sourceString, 0, 1));
        $this->sourceString = \substr($this->sourceString, 1);

        return $byte;
    }
}
