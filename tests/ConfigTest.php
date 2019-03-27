<?php

declare(strict_types=1);
use berbeflo\Brainfuck\Config;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class ConfigTest extends TestCase
{
    public function testDefaultInput()
    {
        $config = new Config();
        $input = $config->getInputObject();
        $this->expectException(RuntimeException::class);
        $input->getNextChar();
    }

    public function testDefaultOutput()
    {
        $config = new Config();
        $output = $config->getOutputObject();
        $this->expectException(RuntimeException::class);
        $output->writeChar(0);
    }

    public function testDefaultValues()
    {
        $config = new Config();
        $this->assertSame(0, $config->getMinRegisterValue());
        $this->assertSame(255, $config->getMaxRegisterValue());
        $this->assertSame(0, $config->getMinPointerValue());
        $this->assertSame(255, $config->getMaxPointerValue());
        $this->assertFalse($config->getWrapOnPointerOverflow());
        $this->assertFalse($config->getWrapOnRegisterOverflow());
        $this->assertSame(255, $config->getMaximumIterations());
    }
}
