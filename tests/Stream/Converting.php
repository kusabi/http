<?php

namespace Kusabi\Http\Tests\Stream;

use Kusabi\Http\Stream;
use Kusabi\Http\Tests\TestCase;

class Converting extends TestCase
{
    /**
     * @covers \Kusabi\Http\Stream::__toString
     */
    public function testCastStringReturnsEmptyStringWhenNotReadable()
    {
        $resource = fopen('php://stdout', 'w');
        fwrite($resource, '.');
        $stream = new Stream($resource);
        $this->assertSame('', (string) $stream);
    }

    /**
     * @covers \Kusabi\Http\Stream::__toString
     */
    public function testCastStringReturnsWholeValue()
    {
        $text = 'this is some test data';
        $resource = fopen('php://temp', 'r+');
        fwrite($resource, $text);
        $stream = new Stream($resource);
        $this->assertSame($text, (string) $stream);
    }

    /**
     * @covers \Kusabi\Http\Stream::pipe
     */
    public function testPipeCopiesItsContents()
    {
        $resourceA = fopen('php://temp', 'w+');
        $resourceB = fopen('php://temp', 'w+');

        fwrite($resourceA, 'this is some test data');
        rewind($resourceA);

        $streamA = new Stream($resourceA);
        $streamB = new Stream($resourceB);

        $streamA->pipe($streamB);
        $streamB->rewind();

        $this->assertSame('this is some test data', (string) $streamA);
        $this->assertSame('this is some test data', (string) $streamB);
    }

    /**
     * @covers \Kusabi\Http\Stream::toString
     */
    public function testToStringReturnsEmptyStringWhenNotReadable()
    {
        $resource = fopen('php://stdout', 'w');
        fwrite($resource, '.');
        $stream = new Stream($resource);
        $this->assertSame('', $stream->toString());
    }

    /**
     * @covers \Kusabi\Http\Stream::toString
     */
    public function testToStringReturnsWholeValue()
    {
        $text = 'this is some test data';
        $resource = fopen('php://temp', 'r+');
        fwrite($resource, $text);
        $stream = new Stream($resource);
        $this->assertSame($text, $stream->toString());
    }
}
