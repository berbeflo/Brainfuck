<?php

declare(strict_types=1);

namespace berbeflo\Brainfuck;

use Exception;

class InterpreterException extends Exception
{
    private ?string $token;
    private ?int $codePoint;

    public function __construct(string $message = "", ?int $codePoint = null, ?string $token = null)
    {
        parent::__construct($message);

        $this->token = $token;
        $this->codePoint = $codePoint;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function getCodePoint(): ?int
    {
        return $this->codePoint;
    }
}
