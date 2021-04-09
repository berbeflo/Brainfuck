<?php

use berbeflo\Brainfuck\Config;
use berbeflo\Brainfuck\Input\BytesFromString;
use berbeflo\Brainfuck\Interpreter;
use berbeflo\Brainfuck\Output\BytesToString;

require_once(__DIR__ . '/../vendor/autoload.php');

$helloWorldProgram = '++++++++++[>+++++++>++++++++++>+++>+<<<<-]>++.>+.+++++++..+++.>++.<<+++++++++++++++.>.+++.------.--------.>+.>.+++.';

$outputObject = new BytesToString();
$brainfuckConfig = new Config();
$brainfuckConfig
    ->setInputObject(new BytesFromString(''))
    ->setOutputObject($outputObject);
$brainfuckInterpreter = new Interpreter($helloWorldProgram, $brainfuckConfig);
$brainfuckInterpreter
    ->prepare()
    ->execute();
    
echo $outputObject->getResult();
