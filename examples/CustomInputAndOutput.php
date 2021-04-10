<?php

use berbeflo\Brainfuck\Config;
use berbeflo\Brainfuck\Interpreter;
use berbeflo\Brainfuck\Definition\Input;
use berbeflo\Brainfuck\Definition\Output;

require_once(__DIR__ . '/../vendor/autoload.php');

$inputObject = new class() implements Input {
    public function getNextChar(): int
    {
        return random_int(0, 10);
    }
};

$outputObject = new class() implements Output {
    public function writeChar(int $char): void
    {
        echo $char . PHP_EOL;
    }
};

$printInput = ',.,.,.';

$brainfuckConfig = new Config();
$brainfuckConfig
    ->setOutputObject($outputObject)
    ->setInputObject($inputObject);
$brainfuckInterpreter = new Interpreter($printInput, $brainfuckConfig);
$brainfuckInterpreter
    ->prepare()
    ->execute();

