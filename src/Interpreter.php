<?php

declare(strict_types=1);
namespace berbeflo\Brainfuck;

use RuntimeException;

final class Interpreter
{
    private $brainfuckCode;
    private $config;

    private $pointer;
    private $memory;

    public function __construct(string $brainfuckCode, Config $config)
    {
        $this->brainfuckCode = $brainfuckCode;
        $this->config = $config;
    }

    public function prepare() : Interpreter
    {
        return $this;
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function execute() : Interpreter
    {
        ['register' => $defaultRegisterValue, 'pointer' => $defaultPointerValue] = $this->getDefaultValues();

        $this->pointer = $defaultPointerValue;
        $this->memory = [];
        $this->memory[$this->pointer] = $defaultRegisterValue;

        for ($currentOperator = 0; $currentOperator < \strlen($this->brainfuckCode); $currentOperator++) {
            switch ($this->brainfuckCode[$currentOperator]) {
                case ',':
                    $this->memory[$this->pointer] = $this->readChar();
                    break;
                case '.':
                    $this->writeChar($this->memory[$this->pointer]);
                    break;
                case '+':
                    $this->memory[$this->pointer]++;
                    $this->checkMemoryValue();
                    break;
                case '-':
                    $this->memory[$this->pointer]--;
                    $this->checkMemoryValue();
                    break;
                case '>':
                    $this->pointer++;
                    $this->checkPointerValue();

                    if (!\array_key_exists($this->pointer, $this->memory)) {
                        $this->memory[$this->pointer] = $defaultRegisterValue;
                    }
                    break;
                case '<':
                    $this->pointer--;
                    $this->checkPointerValue();

                    if (!\array_key_exists($this->pointer, $this->memory)) {
                        $this->memory[$this->pointer] = $defaultRegisterValue;
                    }
                    break;
                default:
                    throw new RuntimeException();
            }
        }

        return $this;
    }

    private function getDefaultValues() : array
    {
        $defaultRegisterValue =
            \min(
                $this->config->getMaxRegisterValue(),
                \max($this->config->getMinRegisterValue(), 0)
            );
        $defaultPointerValue = \min(
            $this->config->getMaxPointerValue(),
            \max($this->config->getMinPointerValue(), 0)
        );

        return ['register' => $defaultRegisterValue, 'pointer' => $defaultPointerValue];
    }

    private function readChar() : int
    {
        $char = $this->config->getInputObject()->getNextChar();

        if ($char > $this->config->getMaxRegisterValue() || $char < $this->config->getMinRegisterValue()) {
            throw new RuntimeException();
        }

        return $char;
    }

    private function writeChar(int $char) : void
    {
        $this->config->getOutputObject()->writeChar($char);
    }

    private function checkPointerValue() : void
    {
        if ($this->pointer > $this->config->getMaxPointerValue()) {
            if ($this->config->getWrapOnPointerOverflow()) {
                $this->pointer = $this->config->getMinPointerValue();

                return;
            }
            throw new RuntimeException();
        }

        if ($this->pointer < $this->config->getMinPointerValue()) {
            if ($this->config->getWrapOnPointerOverflow()) {
                $this->pointer = $this->config->getMaxPointerValue();

                return;
            }
            throw new RuntimeException();
        }
    }

    private function checkMemoryValue() : void
    {
        $currentValue = $this->memory[$this->pointer];

        if ($currentValue > $this->config->getMaxRegisterValue()) {
            if ($this->config->getWrapOnRegisterOverflow()) {
                $this->memory[$this->pointer] = $this->config->getMinRegisterValue();

                return;
            }
            throw new RuntimeException();
        }

        if ($currentValue < $this->config->getMinRegisterValue()) {
            if ($this->config->getWrapOnRegisterOverflow()) {
                $this->memory[$this->pointer] = $this->config->getMaxRegisterValue();

                return;
            }
            throw new RuntimeException();
        }
    }
}
