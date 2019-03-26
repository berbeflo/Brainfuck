<?php

declare(strict_types=1);
namespace berbeflo\Brainfuck\Input;

interface Input
{
    public function getNextChar() : int;
}
