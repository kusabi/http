<?php

namespace Kusabi\Http\Tests\Message;

use Kusabi\Http\Stream;
use Kusabi\Http\Tests\MessageTestCase;
use Psr\Http\Message\StreamInterface;

class Creating extends MessageTestCase
{
    /**
     * @covers \Kusabi\Http\Message::__construct
     * @covers \Kusabi\Http\Message::getBody
     */
    public function testBodyIsAlwaysStream()
    {
        $stub = $this->createInstance();
        $this->assertSame('', (string) $stub->getBody());
        $this->assertInstanceOf(StreamInterface::class, $stub->getBody());

        $stub = $this->createInstance([], fopen('php://temp', 'w+'));
        $this->assertSame('', (string) $stub->getBody());
        $this->assertInstanceOf(StreamInterface::class, $stub->getBody());

        $stub = $this->createInstance([], 'test');
        $this->assertSame('test', (string) $stub->getBody());
        $this->assertInstanceOf(StreamInterface::class, $stub->getBody());

        $stream = new Stream(fopen('php://temp', 'w+'));
        $stream->write('test');
        $stream->rewind();
        $stub = $this->createInstance([], $stream);
        $this->assertSame('test', (string) $stub->getBody());
        $this->assertSame($stream, $stub->getBody());
        $this->assertInstanceOf(StreamInterface::class, $stub->getBody());
    }

    /**
     * @covers \Kusabi\Http\Message::__construct
     */
    public function testDefaultProtocolVersion()
    {
        $stub = $this->createInstance();
        $this->assertSame('1.1', $stub->getProtocolVersion());
    }

    /**
     * @covers \Kusabi\Http\Message::__construct
     */
    public function testSetDefaultProtocolVersion()
    {
        $stub = $this->createInstance([], null, '1.0');
        $this->assertSame('1.0', $stub->getProtocolVersion());
    }
}
