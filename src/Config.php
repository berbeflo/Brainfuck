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
    private ?Input $inputObject = null;
    private ?Output $outputObject = null;
    private int $minRegisterValue = 0;
    private int $maxRegisterValue = 255;
    private int $minPointerValue = 0;
    private int $maxPointerValue = 255;
    private int $maximumIterations = 255;
    private bool $wrapOnRegisterOverflow = false;
    private bool $wrapOnPointerOverflow = false;
    private bool $allowUnknownTokens = true;

    public function __construct()
    {
    }

    public function getInputObject(): Input
    {
        if ($this->inputObject === null) {
            return new class() implements Input {
                public function getNextChar(): int
                {
                    throw new RuntimeException('Default Input object cannot provide any data.');
                }
            };
        }

        return $this->inputObject;
    }

    public function setInputObject(Input $input): Config
    {
        $this->inputObject = $input;

        return $this;
    }

    public function getMaxPointerValue(): int
    {
        return $this->maxPointerValue;
    }

    public function setMaxPointerValue(int $maxPointerValue): Config
    {
        $this->maxPointerValue = $maxPointerValue;

        return $this;
    }

    public function getWrapOnPointerOverflow(): bool
    {
        return $this->wrapOnPointerOverflow;
    }

    public function setWrapOnPointerOverflow(bool $wrapOnPointerOverflow): Config
    {
        $this->wrapOnPointerOverflow = $wrapOnPointerOverflow;

        return $this;
    }

    public function getAllowUnknownTokens(): bool
    {
        return $this->allowUnknownTokens;
    }

    public function setAllowUnknownTokens(bool $allowUnknownTokens): Config
    {
        $this->allowUnknownTokens = $allowUnknownTokens;

        return $this;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function getOutputObject(): Output
    {
        if ($this->outputObject === null) {
            return new class() implements Output {
                public function writeChar(int $char): void
                {
                    throw new RuntimeException('Default Output object does not accept any data.');
                }
            };
        }

        return $this->outputObject;
    }

    public function setOutputObject(Output $outputObject): Config
    {
        $this->outputObject = $outputObject;

        return $this;
    }

    public function getMinRegisterValue(): int
    {
        return $this->minRegisterValue;
    }

    public function setMinRegisterValue(int $minRegisterValue): Config
    {
        $this->minRegisterValue = $minRegisterValue;

        return $this;
    }

    public function getMaxRegisterValue(): int
    {
        return $this->maxRegisterValue;
    }

    public function setMaxRegisterValue(int $maxRegisterValue): Config
    {
        $this->maxRegisterValue = $maxRegisterValue;

        return $this;
    }

    public function getMinPointerValue(): int
    {
        return $this->minPointerValue;
    }

    public function setMinPointerValue(int $minPointerValue): Config
    {
        $this->minPointerValue = $minPointerValue;

        return $this;
    }

    public function getWrapOnRegisterOverflow(): bool
    {
        return $this->wrapOnRegisterOverflow;
    }

    public function setWrapOnRegisterOverflow(bool $wrapOnRegisterOverflow): Config
    {
        $this->wrapOnRegisterOverflow = $wrapOnRegisterOverflow;

        return $this;
    }

    public function getMaximumIterations(): int
    {
        return $this->maximumIterations;
    }

    public function setMaximumIterations(int $maximumIterations): Config
    {
        $this->maximumIterations = $maximumIterations;

        return $this;
    }

    /**
     * @return array<string, int>
     */
    public function getDefaultValues(): array
    {
        $defaultRegisterValue =
            \min(
                $this->getMaxRegisterValue(),
                \max($this->getMinRegisterValue(), 0)
            );
        $defaultPointerValue = \min(
            $this->getMaxPointerValue(),
            \max($this->getMinPointerValue(), 0)
        );

        return ['register' => $defaultRegisterValue, 'pointer' => $defaultPointerValue];
    }
}
