<?php

declare(strict_types=1);

namespace berbeflo\Brainfuck\Definition;

interface Resettable
{
    public function reset(): void;
}
