<?php

declare(strict_types=1);
namespace berbeflo\Brainfuck\Input;

use RuntimeException;

class BytesFromString implements Input
{
    private $sourceString;
    private $backupString;

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
