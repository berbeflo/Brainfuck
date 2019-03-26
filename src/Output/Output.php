<?php

declare(strict_types=1);
namespace berbeflo\Brainfuck\Output;

interface Output
{
    public function writeChar(int $char);
}
