<?php

namespace Tests;

use Kusabi\Http\AbstractMessage;
use Kusabi\Stream\Stream;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;

class MessageBodyTest extends TestCase
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

    public function testWithBodyReturnsNewInstance()
    {
        $stub = $this->createInstance();
        $new = $stub->withBody(new Stream(fopen('php://temp', 'w+')));
        $this->assertNotSame($stub, $new);
    }

    public function testWithBodyReturnsSameInstanceIfSame()
    {
        $stream = new Stream(fopen('php://temp', 'w+'));
        $stub = $this->createInstance([], $stream);
        $new = $stub->withBody($stream);
        $this->assertSame($stub, $new);
    }
}
