<?php

namespace Kusabi\Http\Tests\StreamFactory;

use InvalidArgumentException;
use Kusabi\Http\Stream;
use Kusabi\Http\StreamFactory;
use Kusabi\Http\Tests\TestCase;

class Creating extends TestCase
{
    /**
     * @covers \Kusabi\Http\StreamFactory::createStream
     */
    public function testCreateStreamCreatesTemporaryResource()
    {
        $factory = new StreamFactory();
        $stream = $factory->createStream('test');
        $this->assertInstanceOf(Stream::class, $stream);
        $this->assertSame('test', $stream->getContents());
    }

    /**
     * @covers \Kusabi\Http\StreamFactory::createStreamFromAny
     */
    public function testCreateStreamFromAnyCreatesTemporaryResource()
    {
        $factory = new StreamFactory();
        $stream = $factory->createStreamFromAny('test');
        $this->assertInstanceOf(Stream::class, $stream);
        $this->assertSame('test', $stream->getContents());
    }

    /**
     * @covers \Kusabi\Http\StreamFactory::createStreamFromAny
     */
    public function testCreateStreamFromAnyFromFile()
    {
        $factory = new StreamFactory();
        $stream = $factory->createStreamFromAny(__DIR__.'/readable_file.txt');
        $this->assertInstanceOf(Stream::class, $stream);
        $this->assertSame('This file should be readable', $stream->getContents());
    }

    /**
     * @covers \Kusabi\Http\StreamFactory::createStreamFromAny
     */
    public function testCreateStreamFromAnyFromInvalid()
    {
        $this->expectException(InvalidArgumentException::class);
        $factory = new StreamFactory();
        $factory->createStreamFromAny([1, 2, 3]);
    }

    /**
     * @covers \Kusabi\Http\StreamFactory::createStreamFromAny
     */
    public function testCreateStreamFromAnyFromResource()
    {
        $factory = new StreamFactory();
        $resource = fopen('php://temp', 'w+');
        $stream = $factory->createStreamFromAny($resource);
        $this->assertInstanceOf(Stream::class, $stream);
    }

    /**
     * @covers \Kusabi\Http\StreamFactory::createStreamFromAny
     */
    public function testCreateStreamFromAnyFromStream()
    {
        $factory = new StreamFactory();
        $other = $factory->createStreamFromAny('test');
        $stream = $factory->createStreamFromAny($other);
        $this->assertInstanceOf(Stream::class, $stream);
        $this->assertSame('test', $stream->getContents());
    }

    /**
     * @covers \Kusabi\Http\StreamFactory::createStreamFromFile
     */
    public function testCreateStreamFromFile()
    {
        $factory = new StreamFactory();
        $stream = $factory->createStreamFromFile(__DIR__.'/readable_file.txt');
        $this->assertInstanceOf(Stream::class, $stream);
        $this->assertSame('This file should be readable', $stream->getContents());
    }

    /**
     * @covers \Kusabi\Http\StreamFactory::createStreamFromFile
     */
    public function testCreateStreamFromInvalidFile()
    {
        $this->expectException(\RuntimeException::class);
        $factory = new StreamFactory();
        $stream = $factory->createStreamFromFile(__DIR__.'/not_real_file.txt');
    }

    /**
     * @covers \Kusabi\Http\StreamFactory::createStreamFromResource
     */
    public function testCreateStreamFromResource()
    {
        $factory = new StreamFactory();
        $resource = fopen('php://temp', 'w+');
        $stream = $factory->createStreamFromResource($resource);
        $this->assertInstanceOf(Stream::class, $stream);
    }
}
