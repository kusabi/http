<?php

namespace Kusabi\Http\Tests\Stream;

use Kusabi\Http\Stream;
use Kusabi\Http\Tests\TestCase;
use RuntimeException;

class Reading extends TestCase
{
    public function providesReadableWritableData()
    {
        return [
            ['r', true, false],
            ['r+', true, true],
            ['w', false, true],
            ['w+', true, true],
            ['a', false, true],
            ['a+', true, true],
            ['x', false, true],
            ['x+', true, true],
            ['c', false, true],
            ['c+', true, true],
        ];
    }

    /**
     * @covers \Kusabi\Http\Stream::eof
     */
    public function testEofKnowsIfWeHaveReachedTheEndOfTheStream()
    {
        $resource = fopen('php://temp', 'r+');
        fwrite($resource, 'this is some test data');
        rewind($resource);
        $stream = new Stream($resource);
        while (!feof($resource)) {
            $this->assertFalse($stream->eof());
            fread($resource, 5);
        }
        $this->assertTrue($stream->eof());
    }

    /**
     * @covers \Kusabi\Http\Stream::getContents
     */
    public function testGetContentsThrowsExceptionWhenNotReadable()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Resource is not readable');
        $resource = fopen('php://stdout', 'w');
        $stream = new Stream($resource);
        $stream->getContents();
    }

    /**
     * @covers \Kusabi\Http\Stream::getContents
     */
    public function testGetContentsWorksCorrectly()
    {
        $resource = fopen('php://temp', 'r+');
        fwrite($resource, 'hello');
        rewind($resource);
        $stream = new Stream($resource);
        $this->assertSame('hello', $stream->getContents());
        fseek($resource, 2);
        $this->assertSame('llo', $stream->getContents());
    }

    /**
     * @covers \Kusabi\Http\Stream::getLine
     */
    public function testGetLine()
    {
        $resource = fopen('php://temp', 'r+');
        fwrite($resource, 'hello;how;are;you');
        rewind($resource);
        $stream = new Stream($resource);
        $this->assertSame('hello', $stream->getLine(null, ';'));
        $this->assertSame('how', $stream->getLine(90, ';'));
        $this->assertSame('are', $stream->getLine(90, ';'));
        $this->assertSame('you', $stream->getLine(90, ';'));

        $stream->rewind();
        $this->assertSame('hello;how;are;you', $stream->getLine());
    }

    /**
     * @covers \Kusabi\Http\Stream::getLine
     */
    public function testGetLineThrowsExceptionWhenNotReadable()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Resource is not readable');
        $resource = fopen('php://stdout', 'w');
        $stream = new Stream($resource);
        $stream->getLine();
    }

    /**
     * @covers \Kusabi\Http\Stream::getMetadata
     */
    public function testGetMetaDataWithKeyReturnsValueOrNull()
    {
        $resource = fopen('php://temp', 'r+');
        $stream = new Stream($resource);
        $this->assertSame('PHP', $stream->getMetadata('wrapper_type'));
        $this->assertSame('TEMP', $stream->getMetadata('stream_type'));
        $this->assertSame('w+b', $stream->getMetadata('mode'));
        $this->assertSame(0, $stream->getMetadata('unread_bytes'));
        $this->assertSame(true, $stream->getMetadata('seekable'));
        $this->assertSame('php://temp', $stream->getMetadata('uri'));
        $this->assertNull($stream->getMetadata('not-real'));
    }

    /**
     * @covers \Kusabi\Http\Stream::getMetadata
     */
    public function testGetMetaDataWithoutKeyReturnsAll()
    {
        $resource = fopen('php://temp', 'r+');
        $stream = new Stream($resource);
        $this->assertSame([
            'wrapper_type' => 'PHP',
            'stream_type' => 'TEMP',
            'mode' => 'w+b',
            'unread_bytes' => 0,
            'seekable' => true,
            'uri' => 'php://temp'
        ], $stream->getMetadata());
    }

    /**
     * @covers \Kusabi\Http\Stream::getMode
     */
    public function testGetMode()
    {
        $stream = new Stream(fopen('php://stdin', 'r+'));
        $this->assertSame('r+', $stream->getMode());
    }

    /**
     * @covers \Kusabi\Http\Stream::getResource
     */
    public function testGetResourceReturnsSameResource()
    {
        $resource = fopen('php://temp', 'r+');
        $stream = new Stream($resource);
        $this->assertSame($resource, $stream->getResource());
    }

    /**
     * @covers \Kusabi\Http\Stream::getSize
     */
    public function testGetSizeReturnsSize()
    {
        $resource = fopen('php://temp', 'r+');
        fwrite($resource, 'this is some test data');
        rewind($resource);
        $stream = new Stream($resource);
        $this->assertSame(22, $stream->getSize());
    }

    /**
     * @covers \Kusabi\Http\Stream::getStat
     */
    public function testGetStatWithKeyReturnsValueOrNull()
    {
        $resource = fopen('php://temp', 'r+');
        $stream = new Stream($resource);
        $this->assertSame(12, $stream->getStat(0));
        $this->assertSame(0, $stream->getStat(1));
        $this->assertSame(33206, $stream->getStat(2));
        $this->assertSame(1, $stream->getStat(3));
        $this->assertSame(0, $stream->getStat(4));
        $this->assertSame(0, $stream->getStat(5));
        $this->assertSame(-1, $stream->getStat(6));
        $this->assertSame(0, $stream->getStat(7));
        $this->assertSame(0, $stream->getStat(8));
        $this->assertSame(0, $stream->getStat(9));
        $this->assertSame(0, $stream->getStat(10));
        $this->assertSame(-1, $stream->getStat(11));
        $this->assertSame(-1, $stream->getStat(12));
        $this->assertSame(12, $stream->getStat('dev'));
        $this->assertSame(0, $stream->getStat('ino'));
        $this->assertSame(33206, $stream->getStat('mode'));
        $this->assertSame(1, $stream->getStat('nlink'));
        $this->assertSame(0, $stream->getStat('uid'));
        $this->assertSame(0, $stream->getStat('gid'));
        $this->assertSame(-1, $stream->getStat('rdev'));
        $this->assertSame(0, $stream->getStat('size'));
        $this->assertSame(0, $stream->getStat('atime'));
        $this->assertSame(0, $stream->getStat('mtime'));
        $this->assertSame(0, $stream->getStat('ctime'));
        $this->assertSame(-1, $stream->getStat('blksize'));
        $this->assertSame(-1, $stream->getStat('blocks'));
        $this->assertNull($stream->getStat('not-real'));
    }

    /**
     * @covers \Kusabi\Http\Stream::getStat
     */
    public function testGetStatWithoutKeyReturnsAll()
    {
        $resource = fopen('php://temp', 'r+');
        $stream = new Stream($resource);
        $this->assertSame([
            0 => 12,
            1 => 0,
            2 => 33206,
            3 => 1,
            4 => 0,
            5 => 0,
            6 => -1,
            7 => 0,
            8 => 0,
            9 => 0,
            10 => 0,
            11 => -1,
            12 => -1,
            'dev' => 12,
            'ino' => 0,
            'mode' => 33206,
            'nlink' => 1,
            'uid' => 0,
            'gid' => 0,
            'rdev' => -1,
            'size' => 0,
            'atime' => 0,
            'mtime' => 0,
            'ctime' => 0,
            'blksize' => -1,
            'blocks' => -1
        ], $stream->getStat());
    }

    /**
     * @covers \Kusabi\Http\Stream::getStreamType
     */
    public function testGetStreamType()
    {
        $stream = new Stream(fopen('php://temp', 'r+'));
        $this->assertSame('TEMP', $stream->getStreamType());
    }

    /**
     * @covers \Kusabi\Http\Stream::getUnreadBytes
     */
    public function testGetUnreadBytes()
    {
        $stream = new Stream(fopen('php://temp', 'r+'));
        $this->assertSame(0, $stream->getUnreadBytes());
    }

    /**
     * @covers \Kusabi\Http\Stream::getUri
     */
    public function testGetUri()
    {
        $stream = new Stream(fopen('php://temp', 'r+'));
        $this->assertSame('php://temp', $stream->getUri());
    }

    /**
     * @covers \Kusabi\Http\Stream::getWrapperType
     */
    public function testGetWrapperType()
    {
        $stream = new Stream(fopen('php://temp', 'r+'));
        $this->assertSame('PHP', $stream->getWrapperType());
    }

    /**
     * @covers \Kusabi\Http\Stream::isLocal
     */
    public function testIsLocal()
    {
        $stream = new Stream(fopen('php://temp', 'r+'));
        $this->assertTrue($stream->isLocal());

        $stream = new Stream(fopen('http://www.google.com', 'r'));
        $this->assertFalse($stream->isLocal());
    }

    /**
     * @covers \Kusabi\Http\Stream::isSeekable
     */
    public function testIsSeekableIsCorrect()
    {
        $stream = new Stream(fopen('php://temp', 'r+'));
        $this->assertTrue($stream->isSeekable());

        $stream = new Stream(fopen('php://stdout', 'r+'));
        $this->assertFalse($stream->isSeekable());
    }

    /**
     * @covers \Kusabi\Http\Stream::read
     */
    public function testReadThrowsExceptionIfNotReadable()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Resource is not readable');
        $stream = new Stream(fopen('php://stdin', 'w'));
        $stream->read(1);
    }

    /**
     * @covers \Kusabi\Http\Stream::read
     */
    public function testReadWorksCorrectly()
    {
        $resource = fopen('php://temp', 'r+');
        fwrite($resource, 'hello ');
        rewind($resource);
        $stream = new Stream($resource);
        $this->assertSame('hel', $stream->read(3));
        $this->assertSame('lo', $stream->read(2));
        $this->assertSame(' ', $stream->read(1));
    }

    /**
     * @param string $mode
     * @param bool $isReadable
     * @param bool $isWritable
     *
     * @dataProvider providesReadableWritableData
     *
     * @covers       \Kusabi\Http\Stream::isReadable
     * @covers       \Kusabi\Http\Stream::isWritable
     */
    public function testReadableAndWritable($mode, $isReadable, $isWritable)
    {
        $stream = new Stream(fopen('php://stdin', $mode));
        $this->assertSame($isReadable, $stream->isReadable());
        $this->assertSame($isWritable, $stream->isWritable());
    }

    /**
     * @covers \Kusabi\Http\Stream::tell
     */
    public function testTellGivesCorrectPosition()
    {
        $resource = fopen('php://temp', 'r+');
        fwrite($resource, 'this is some test data');
        rewind($resource);
        $stream = new Stream($resource);
        $this->assertSame(0, $stream->tell());
        fseek($resource, 5);
        $this->assertSame(5, $stream->tell());
    }
}
