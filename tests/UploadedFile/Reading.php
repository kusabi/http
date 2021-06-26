<?php

namespace Kusabi\Http\Tests\UploadedFile;

use InvalidArgumentException;
use Kusabi\Http\Stream;
use Kusabi\Http\Tests\UploadedFileTestCase;
use Kusabi\Http\UploadedFile;
use Psr\Http\Message\StreamInterface;

class Reading extends UploadedFileTestCase
{
    /**
     * @covers \Kusabi\Http\UploadedFile::getClientFilename
     */
    public function testGetClientFilename()
    {
        $uploadedFile = new UploadedFile('test', 4, 0, 'test.txt', 'txt');
        $this->assertSame('test.txt', $uploadedFile->getClientFilename());
    }

    /**
     * @covers \Kusabi\Http\UploadedFile::getClientFilename
     */
    public function testGetClientFilenameIsEmptyWhenNotProvided()
    {
        $uploadedFile = new UploadedFile('test', 4);
        $this->assertSame('', $uploadedFile->getClientFilename());
    }

    /**
     * @covers \Kusabi\Http\UploadedFile::getClientMediaType
     */
    public function testGetClientMediaType()
    {
        $uploadedFile = new UploadedFile('test', 4, 0, 'test.txt', 'txt');
        $this->assertSame('txt', $uploadedFile->getClientMediaType());
    }

    /**
     * @covers \Kusabi\Http\UploadedFile::getClientMediaType
     */
    public function testGetClientMediaTypeIsEmptyWhenNotProvided()
    {
        $uploadedFile = new UploadedFile('test', 4);
        $this->assertSame('', $uploadedFile->getClientMediaType());
    }

    /**
     * @covers \Kusabi\Http\UploadedFile::getSize
     */
    public function testGetSize()
    {
        $uploadedFile = new UploadedFile('test', 4);
        $this->assertSame(4, $uploadedFile->getSize());
    }

    /**
     * @covers \Kusabi\Http\UploadedFile::getStream
     */
    public function testGetStreamFromFile()
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
     * @covers \Kusabi\Http\UploadedFile::getStream
     */
    public function testGetStreamFromResource()
    {
        $resource = fopen('php://temp', 'w+');
        fwrite($resource, 'test');
        $uploadedFile = new UploadedFile($resource, 4);
        $stream = $uploadedFile->getStream();
        $this->assertInstanceOf(StreamInterface::class, $stream);
        $this->assertSame('test', (string) $stream);
    }

    /**
     * @covers \Kusabi\Http\UploadedFile::getStream
     */
    public function testGetStreamFromStream()
    {
        $streamA = new Stream(fopen('php://temp', 'w+'));
        $streamA->write('test');
        $uploadedFile = new UploadedFile($streamA, 4);
        $stream = $uploadedFile->getStream();
        $this->assertInstanceOf(StreamInterface::class, $stream);
        $this->assertSame($stream, $streamA);
        $this->assertSame('test', (string) $stream);
    }

    /**
     * @covers \Kusabi\Http\UploadedFile::getStream
     */
    public function testGetStreamFromString()
    {
        $uploadedFile = new UploadedFile('test', 4);
        $stream = $uploadedFile->getStream();
        $this->assertInstanceOf(StreamInterface::class, $stream);
        $this->assertInstanceOf(Stream::class, $stream);
        $this->assertSame('test', (string) $stream);
    }

    /**
     * @covers \Kusabi\Http\UploadedFile::isMoved
     */
    public function testIsMoved()
    {
        $uploadTo = $this->getTemporaryLocation('uploadedFile.txt');
        $uploadedFile = new UploadedFile('test', 4);
        $this->assertFalse($uploadedFile->isMoved());
        $uploadedFile->moveTo($uploadTo);
        $this->assertTrue($uploadedFile->isMoved());
    }

    /**
     * @param int $error
     * @param bool $isOk
     *
     * @dataProvider providesValidErrorCodes
     *
     * @covers       \Kusabi\Http\UploadedFile::isOk
     */
    public function testIsOkMethod(int $error, bool $isOk)
    {
        $uploadedFile = new UploadedFile('test', 4, $error);
        $this->assertSame($uploadedFile->isOk(), $isOk);
    }

    /**
     * @param $file
     *
     * @dataProvider providesInvalidFiles
     */
    public function testThrowsExceptionForInvalidFileData($file)
    {
        $this->expectException(InvalidArgumentException::class);
        new UploadedFile($file, 4);
    }
}
