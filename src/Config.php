<?php

declare(strict_types=1);
namespace berbeflo\Brainfuck;

use berbeflo\Brainfuck\Definition\Input;
use berbeflo\Brainfuck\Definition\Output;
use RuntimeException;

/**
 * @SuppressWarnings(PHPMD.LongVariable)
 */
final class Config
{
    private $inputObject;
    private $outputObject;
    private $minRegisterValue = 0;
    private $maxRegisterValue = 255;
    private $minPointerValue = 0;
    private $maxPointerValue = 255;
    private $wrapOnRegisterOverflow = false;
    private $wrapOnPointerOverflow = false;

    public function __construct()
    {
    }

    public function getInputObject() : Input
    {
        if ($this->inputObject === null) {
            return new class() implements Input {
                public function getNextChar() : int
                {
                    throw new RuntimeException();
                }
            };
        }

        return $this->inputObject;
    }

    public function setInputObject(Input $input) : Config
    {
        $this->inputObject = $input;

        return $this;
    }

    public function getMaxPointerValue() : int
    {
        return $this->maxPointerValue;
    }

    public function setMaxPointerValue(int $maxPointerValue) : Config
    {
        $this->maxPointerValue = $maxPointerValue;

        return $this;
    }

    public function getWrapOnPointerOverflow() : bool
    {
        return $this->wrapOnPointerOverflow;
    }

    public function setWrapOnPointerOverflow(bool $wrapOnPointerOverflow) : Config
    {
        $this->wrapOnPointerOverflow = $wrapOnPointerOverflow;

        return $this;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function getOutputObject() : Output
    {
        if ($this->outputObject === null) {
            return new class() implements Output {
                public function writeChar(int $char) : void
                {
                    throw new RuntimeException();
                }
            };
        }

        return $this->outputObject;
    }

    public function setOutputObject(Output $outputObject) : Config
    {
        $this->outputObject = $outputObject;

        return $this;
    }

    public function getMinRegisterValue() : int
    {
        return $this->minRegisterValue;
    }

    public function setMinRegisterValue(int $minRegisterValue) : Config
    {
        $this->minRegisterValue = $minRegisterValue;

        return $this;
    }

    public function getMaxRegisterValue() : int
    {
        return $this->maxRegisterValue;
    }

    public function setMaxRegisterValue(int $maxRegisterValue) : Config
    {
        $this->maxRegisterValue = $maxRegisterValue;

        return $this;
    }

    public function getMinPointerValue() : int
    {
        return $this->minPointerValue;
    }

    public function setMinPointerValue(int $minPointerValue) : Config
    {
        $this->minPointerValue = $minPointerValue;

        return $this;
    }

    public function getWrapOnRegisterOverflow() : bool
    {
        return $this->wrapOnRegisterOverflow;
    }

    public function setWrapOnRegisterOverflow(bool $wrapOnRegisterOverflow) : Config
    {
        $this->wrapOnRegisterOverflow = $wrapOnRegisterOverflow;

        return $this;
    }
}
