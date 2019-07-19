<?php

namespace Tests;

use InvalidArgumentException;
use Kusabi\Http\UploadedFile;
use Kusabi\Stream\Stream;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;

class UploadedFileStreamTest extends TestCase
{
    public function testCreateStreamFromString()
    {
        $uploadedFile = new UploadedFile('test', 4);
        $stream = $uploadedFile->getStream();
        $this->assertInstanceOf(StreamInterface::class, $stream);
        $this->assertSame('test', (string) $stream);
    }

    public function testCreateStreamFromResource()
    {
        $resource = fopen('php://temp', 'w+');
        fwrite($resource, 'test');
        $uploadedFile = new UploadedFile($resource, 4);
        $stream = $uploadedFile->getStream();
        $this->assertInstanceOf(StreamInterface::class, $stream);
        $this->assertSame('test', (string) $stream);
    }

    public function testCreateStreamFromStream()
    {
        $streamA = new Stream(fopen('php://temp', 'w+'));
        $streamA->write('test');
        $uploadedFile = new UploadedFile($streamA, 4);
        $stream = $uploadedFile->getStream();
        $this->assertInstanceOf(StreamInterface::class, $stream);
        $this->assertSame($stream, $streamA);
        $this->assertSame('test', (string) $stream);
    }

    public function testCreateStreamFromFile()
    {
        $filename = tempnam('/tmp', 'FOO');
        $resource = fopen($filename, 'w+');
        fwrite($resource, 'test');
        fclose($resource);

        $uploadedFile = new UploadedFile($filename, 4);
        $stream = $uploadedFile->getStream();
        $this->assertInstanceOf(StreamInterface::class, $stream);
        $this->assertSame('test', (string) $stream);
    }

    /**
     * @param $file
     *
     * @dataProvider invalidFileProvider
     */
    public function testThrowsExceptionForInvalidFileData($file)
    {
        $this->expectException(InvalidArgumentException::class);
        new UploadedFile($file, 4);
    }

    public function invalidFileProvider()
    {
        return [
            'null' => [null],
            'false' => [false],
            'true' => [true],
            'integer' => [1],
            'float' => [1.1],
            'array' => [[]],
            'object' => [(object) []],
        ];
    }
}
