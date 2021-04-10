<?php

use berbeflo\Brainfuck\Config;
use berbeflo\Brainfuck\Input\NumbersFromArray;
use berbeflo\Brainfuck\Interpreter;
use berbeflo\Brainfuck\Output\NumbersToArray;

require_once(__DIR__ . '/../vendor/autoload.php');
$addNumbersProgram = '
,>,     // Read in the numbers
<       // Go back to first number
[>+<-]  // Increase second number and decrease first number while first number is not 0
>.      // Print the result
';

$outputObject = new NumbersToArray();
$brainfuckConfig = new Config();
$brainfuckConfig
    ->setOutputObject($outputObject)
    ->setInputObject(new NumbersFromArray([7, 3]));
$brainfuckInterpreter = new Interpreter($addNumbersProgram, $brainfuckConfig);
$brainfuckInterpreter
    ->prepare()
    ->execute();

echo implode('', $outputObject->getResult());
