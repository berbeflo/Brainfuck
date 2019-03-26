<?php

declare(strict_types=1);
namespace berbeflo\Brainfuck;

use berbeflo\Brainfuck\Input\Input;
use berbeflo\Brainfuck\Output\Output;
use RuntimeException;

/**
 * @SuppressWarnings(PHPMD.LongVariable)
 */
final class Config
{
    private $inputObject;
    private $outputObject;
    private $maxPointerValue = 255;
    private $wrapOnPointerOverflow = false;

    public function __construct()
    {
    }

    public function getInputObject() : Input
    {
        if ($this->inputObject === null) {
            // TODO
            throw new RuntimeException();
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

    public function getOutputObject() : Output
    {
        if ($this->outputObject === null) {
            // TODO
            throw new RuntimeException();
        }

        return $this->outputObject;
    }

    public function setOutputObject(Output $outputObject) : Config
    {
        $this->outputObject = $outputObject;

        return $this;
    }
}
