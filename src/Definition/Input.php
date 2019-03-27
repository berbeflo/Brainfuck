<?php

declare(strict_types=1);
namespace berbeflo\Brainfuck\Definition;

interface Input
{
    public function getNextChar() : int;
}
