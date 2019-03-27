<?php

declare(strict_types=1);
namespace berbeflo\Brainfuck\Definition;

interface Output
{
    public function writeChar(int $char);
}
