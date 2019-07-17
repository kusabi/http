<?php

namespace Tests;

use Kusabi\Http\AbstractMessage;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class MessageProtocolTest extends TestCase
{
    /**
     * Create a stub implementation of the abstract class
     *
     * @param array $headers
     * @param null $body
     * @param string $protocol
     *
     * @return AbstractMessage|MockObject
     */
    protected function createInstance(array $headers = [], $body = null, string $protocol = '1.1')
    {
        return $this->getMockForAbstractClass(AbstractMessage::class, [
            $headers,
            $body,
            $protocol
        ]);
    }

    public function testGetProtocolVersionDefault()
    {
        $stub = $this->createInstance();
        $this->assertSame('1.1', $stub->getProtocolVersion());
    }

    public function testGetProtocolVersionReturnsProtocol()
    {
        $stub = $this->createInstance([], null, '1.0');
        $this->assertSame('1.0', $stub->getProtocolVersion());
    }

    public function testSetProtocolVersion()
    {
        $stub = $this->createInstance();
        $stub->setProtocolVersion('1.1');
        $this->assertSame('1.1', $stub->getProtocolVersion());
        $stub->setProtocolVersion('1.0');
        $this->assertSame('1.0', $stub->getProtocolVersion());
    }

    public function testSetProtocolVersionMustBeAVersionNumber()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Invalid protocol version format');
        $stub = $this->createInstance();
        $stub->setProtocolVersion('v1.0');
    }

    public function testWithProtocolVersionUpdatesAClonedInstance()
    {
        $stub = $this->createInstance();
        $result = $stub->withProtocolVersion('1.0');
        $this->assertSame('1.0', $result->getProtocolVersion());
        $this->assertSame('1.1', $stub->getProtocolVersion());
    }

    public function testWithProtocolVersionMustBeAVersionNumber()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Invalid protocol version format');
        $stub = $this->createInstance();
        $stub->withProtocolVersion('v1.0');
    }

    public function testWithProtocolReturnsSameInstanceIfNotChanged()
    {
        $stub = $this->createInstance();
        $stub->setProtocolVersion('1.0');
        $other = $stub->withProtocolVersion('1.0');
        $this->assertSame($stub, $other);
    }
}
