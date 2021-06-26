<?php

namespace Kusabi\Http\Tests\Stream;

use Kusabi\Http\Stream;
use Kusabi\Http\Tests\TestCase;
use RuntimeException;

class Modifying extends TestCase
{
    /**
     * @covers \Kusabi\Http\Stream::close
     */
    public function testCloseClosesTheResource()
    {
        $resource = fopen('php://temp', 'r+');
        $stream = new Stream($resource);
        $stream->close();
        $this->assertNotEquals('stream', get_resource_type($resource));
    }

    /**
     * @covers \Kusabi\Http\Stream::detach
     */
    public function testDetachReturnsAndRemovesTheResource()
    {
        $resource = fopen('php://temp', 'r+');
        $stream = new Stream($resource);
        $this->assertSame($resource, $stream->detach());
        $this->assertNull($stream->getResource());
        $this->assertNull($stream->detach());
    }

    /**
     * @covers \Kusabi\Http\Stream::rewind
     */
    public function testRewindThrowsExceptionIfNotSeekable()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Resource is not seekable');
        $stream = new Stream(fopen('php://stdout', 'r+'));
        $stream->rewind();
    }

    /**
     * @covers \Kusabi\Http\Stream::rewind
     */
    public function testRewindWorks()
    {
        $text = 'this is some test data';
        $resource = fopen('php://temp', 'r+');
        fwrite($resource, $text);
        rewind($resource);
        $stream = new Stream($resource);

        $stream->seek(10);
        $this->assertSame(10, $stream->tell());
        $stream->rewind();
        $this->assertSame(0, $stream->tell());
    }

    /**
     * @covers \Kusabi\Http\Stream::seek
     */
    public function testSeekThrowsExceptionIfNotSeekable()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Resource is not seekable');
        $stream = new Stream(fopen('php://stdout', 'r+'));
        $stream->seek(1);
    }

    /**
     * @covers \Kusabi\Http\Stream::seek
     */
    public function testSeekWorksAsExpected()
    {
        $text = 'this is some test data';
        $resource = fopen('php://temp', 'r+');
        fwrite($resource, $text);
        rewind($resource);
        $stream = new Stream($resource);

        $stream->seek(1);
        $this->assertSame(1, $stream->tell());

        $stream->seek(1, SEEK_CUR);
        $this->assertSame(2, $stream->tell());

        $stream->seek(1, SEEK_SET);
        $this->assertSame(1, $stream->tell());

        $stream->seek(0, SEEK_END);
        $this->assertSame(strlen($text), $stream->tell());
    }

    /**
     * @covers \Kusabi\Http\Stream::write
     */
    public function testWriteWorksCorrectly()
    {
        $resource = fopen('php://temp', 'r+');
        fwrite($resource, 'hello ');
        $stream = new Stream($resource);
        $this->assertSame(6, ftell($resource));
        $stream->write('6 more');
        $this->assertSame(12, ftell($resource));
        rewind($resource);
        $this->assertSame('hello 6 more', stream_get_contents($resource));
    }

    /**
     * @covers \Kusabi\Http\Stream::write
     */
    public function testWriteThrowsExceptionIfNotWritable()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Resource is not writable');
        $stream = new Stream(fopen('php://stdin', 'r'));
        $stream->write('test');
    }
}
