<?php

declare(strict_types=1);
namespace berbeflo\Brainfuck;

final class Interpreter
{
    private $brainfuckCode;
    private $config;

    public function __construct(string $brainfuckCode, Config $config)
    {
        $this->brainfuckCode = $brainfuckCode;
        $this->config = $config;
    }

    public function prepare() : void
    {
    }

    public function execute() : void
    {
    }
}
