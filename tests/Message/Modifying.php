<?php

namespace Kusabi\Http\Tests\Message;

use InvalidArgumentException;
use Kusabi\Http\Stream;
use Kusabi\Http\StreamFactory;
use Kusabi\Http\Tests\MessageTestCase;
use RuntimeException;

class Modifying extends MessageTestCase
{
    /**
     * @covers \Kusabi\Http\Message::addHeader
     * @covers \Kusabi\Http\Message::isValidHeaderKey
     * @covers \Kusabi\Http\Message::normaliseHeaderKey
     */
    public function testAddHeader()
    {
        $stub = $this->createInstance();
        $stub->addHeader('test', 'foo');
        $this->assertSame(['foo'], $stub->getHeader('test'));
        $stub->addHeader('test', 'bar');
        $this->assertSame(['foo', 'bar'], $stub->getHeader('test'));
        $stub->addHeader('TeST', 'baz');
        $this->assertSame(['foo', 'bar', 'baz'], $stub->getHeader('test'));
        $this->assertSame(['test' => ['foo', 'bar', 'baz']], $stub->getHeaders());
    }

    /**
     * @param mixed $key
     *
     * @dataProvider providesInvalidHeaderKeys
     *
     * @covers       \Kusabi\Http\Message::addHeader
     */
    public function testAddHeaderThrowsExceptionForInvalidHeaderKey($key)
    {
        $this->expectException(InvalidArgumentException::class);
        $stub = $this->createInstance();
        $stub->addHeader($key, 'foo');
    }

    /**
     * @covers \Kusabi\Http\Message::removeHeader
     */
    public function testRemoveHeader()
    {
        $stub = $this->createInstance();
        $stub->addHeader('foo', 'bar');
        $stub->addHeader('baz', 'boss');
        $stub->removeHeader('foo');
        $this->assertFalse($stub->hasHeader('foo'));
        $this->assertEquals(['baz' => 'boss'], $stub->getHeaders());
    }

    /**
     * @param mixed $key
     *
     * @dataProvider providesInvalidHeaderKeys
     *
     * @covers       \Kusabi\Http\Message::removeHeader
     */
    public function testRemoveHeaderInvalidKeys($key)
    {
        $this->expectException(InvalidArgumentException::class);
        $stub = $this->createInstance();
        $stub->addHeader('foo', 'bar');
        $stub->addHeader('baz', 'boss');
        $stub->removeHeader($key);
    }

    /**
     * @covers \Kusabi\Http\Message::setBody
     */
    public function testSetBody()
    {
        $streamFactory = new StreamFactory();
        $stub = $this->createInstance();
        $body = $stub->getBody();
        $stub->setBody($streamFactory->createStreamFromAny('test'));
        $this->assertNotSame($body, $stub->getBody());
        $this->assertSame('test', $stub->getBody()->getContents());
    }

    /**
     * @covers \Kusabi\Http\Message::setHeader
     */
    public function testSetHeaderOverwritesExistingHeader()
    {
        $stub = $this->createInstance(['FoO' => 'bAr', 'BAZ' => 'FoO']);
        $stub->setHeader('FOO', 'BAZ');
        $this->assertEquals(['FOO' => 'BAZ', 'BAZ' => 'FoO'], $stub->getHeaders());
    }

    /**
     * @param mixed $key
     *
     * @dataProvider providesInvalidHeaderKeys
     *
     * @covers       \Kusabi\Http\Message::setHeader
     */
    public function testSetHeaderThrowsExceptionForInvalidHeaderKey($key)
    {
        $this->expectException(InvalidArgumentException::class);
        $stub = $this->createInstance();
        $stub->setHeader($key, 'foo');
    }

    /**
     * @covers \Kusabi\Http\Message::setHeaders
     */
    public function testSetHeadersOverwritesExistingHeaders()
    {
        $stub = $this->createInstance(['FoO' => 'bAr', 'BAZ' => 'FoO']);
        $stub->setHeaders(['a' => 'b', 'C' => 'D']);
        $this->assertSame(['a' => 'b', 'C' => 'D'], $stub->getHeaders());
    }

    /**
     * @covers \Kusabi\Http\Message::setHeaders
     */
    public function testSetHeadersThrowsExceptionForInvalidTypes()
    {
        $this->expectException(InvalidArgumentException::class);
        $stub = $this->createInstance();
        $stub->setHeaders([
            '' => 'test'
        ]);
    }

    /**
     * @covers \Kusabi\Http\Message::withoutHeader
     */
    public function testSetProtocolVersion()
    {
        $stub = $this->createInstance();
        $stub->setProtocolVersion('1.1');
        $this->assertSame('1.1', $stub->getProtocolVersion());
        $stub->setProtocolVersion('1.0');
        $this->assertSame('1.0', $stub->getProtocolVersion());
    }

    /**
     * @covers \Kusabi\Http\Message::setProtocolVersion
     */
    public function testSetProtocolVersionMustBeAVersionNumber()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Invalid protocol version format');
        $stub = $this->createInstance();
        $stub->setProtocolVersion('v1.0');
    }

    /**
     * @covers \Kusabi\Http\Message::withAddedHeader
     */
    public function testWithAddedHeader()
    {
        $stub = $this->createInstance();
        $result = $stub->withAddedHeader('test', 'foo');
        $this->assertSame(['foo'], $result->getHeader('test'));
        $result = $result->withAddedHeader('test', 'bar');
        $this->assertSame(['foo', 'bar'], $result->getHeader('test'));
        $result = $result->withAddedHeader('TeST', 'baz');
        $this->assertSame(['foo', 'bar', 'baz'], $result->getHeader('test'));
        $this->assertSame(['test' => ['foo', 'bar', 'baz']], $result->getHeaders());
        $this->assertSame([], $stub->getHeaders());
    }

    /**
     * @param mixed $key
     *
     * @dataProvider providesInvalidHeaderKeys
     *
     * @covers       \Kusabi\Http\Message::withAddedHeader
     */
    public function testWithAddedHeaderThrowsExceptionForInvalidHeaderKey($key)
    {
        $this->expectException(InvalidArgumentException::class);
        $stub = $this->createInstance();
        $stub->withAddedHeader($key, 'foo');
    }

    /**
     * @covers \Kusabi\Http\Message::withBody
     */
    public function testWithBodyReturnsNewInstance()
    {
        $stub = $this->createInstance();
        $new = $stub->withBody(new Stream(fopen('php://temp', 'w+')));
        $this->assertNotSame($stub, $new);
    }

    /**
     * @covers \Kusabi\Http\Message::withBody
     */
    public function testWithBodyReturnsSameInstanceIfSame()
    {
        $stream = new Stream(fopen('php://temp', 'w+'));
        $stub = $this->createInstance([], $stream);
        $new = $stub->withBody($stream);
        $this->assertSame($stub, $new);
    }

    /**
     * @covers \Kusabi\Http\Message::withHeader
     */
    public function testWithHeader()
    {
        $stub = $this->createInstance();
        $result = $stub->withHeader('test', 'foo');
        $this->assertSame(['foo'], $result->getHeader('test'));
        $result = $result->withHeader('test', 'bar');
        $this->assertSame(['bar'], $result->getHeader('test'));
        $result = $result->withHeader('TeST', 'baz');
        $this->assertSame(['baz'], $result->getHeader('test'));
        $this->assertSame(['TeST' => 'baz'], $result->getHeaders());
        $this->assertSame([], $stub->getHeaders());
    }

    /**
     * @covers \Kusabi\Http\Message::withHeader
     */
    public function testWithHeaderIsCaseInsensitive()
    {
        $stub = $this->createInstance(['FoO' => 'bAr', 'BAZ' => 'FoO']);
        $stub = $stub->withHeader('FOO', 'baz');
        $this->assertEquals(['FOO' => 'baz', 'BAZ' => 'FoO'], $stub->getHeaders());
        $this->assertSame(['baz'], $stub->getHeader('Foo'));
        $this->assertSame(['baz'], $stub->getHeader('FoO'));
        $this->assertSame(['baz'], $stub->getHeader('FOO'));
        $this->assertTrue($stub->hasHeader('foo'));
        $this->assertTrue($stub->hasHeader('FoO'));
        $this->assertTrue($stub->hasHeader('FOO'));
    }

    /**
     * @param mixed $key
     *
     * @dataProvider providesInvalidHeaderKeys()
     *
     * @covers       \Kusabi\Http\Message::withHeader
     */
    public function testWithHeaderThrowsExceptionForInvalidHeaderKey($key)
    {
        $this->expectException(InvalidArgumentException::class);
        $stub = $this->createInstance();
        $stub->withHeader($key, 'foo');
    }

    /**
     * @covers \Kusabi\Http\Message::withHeaders
     */
    public function testWithHeaders()
    {
        $headers = ['FoO' => 'bAr', 'BAZ' => 'FoO'];
        $stub = $this->createInstance();
        $instance = $stub->withHeaders($headers);
        $this->assertSame($headers, $instance->getHeaders());
        $this->assertNotSame($instance, $stub);
    }

    /**
     * @covers \Kusabi\Http\Message::withHeaders
     */
    public function testWithHeadersReturnsSameInstanceIfUnchanged()
    {
        $headers = ['FoO' => 'bAr', 'BAZ' => 'FoO'];
        $stub = $this->createInstance($headers);
        $new = $stub->withHeaders($headers);
        $this->assertSame($headers, $new->getHeaders());
        $this->assertSame($new, $stub);
    }

    /**
     * @covers \Kusabi\Http\Message::withHeaders
     */
    public function testWithHeadersThrowsExceptionForInvalidHeaderKey()
    {
        $this->expectException(InvalidArgumentException::class);
        $stub = $this->createInstance();
        $stub->setHeaders(['' => 'foo']);
    }

    /**
     * @covers \Kusabi\Http\Message::withProtocolVersion
     */
    public function testWithProtocolReturnsSameInstanceIfNotChanged()
    {
        $stub = $this->createInstance();
        $stub->setProtocolVersion('1.0');
        $other = $stub->withProtocolVersion('1.0');
        $this->assertSame($stub, $other);
    }

    /**
     * @covers \Kusabi\Http\Message::withProtocolVersion
     */
    public function testWithProtocolVersionMustBeAVersionNumber()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Invalid protocol version format');
        $stub = $this->createInstance();
        $stub->withProtocolVersion('v1.0');
    }

    /**
     * @covers \Kusabi\Http\Message::withProtocolVersion
     */
    public function testWithProtocolVersionUpdatesAClonedInstance()
    {
        $stub = $this->createInstance();
        $result = $stub->withProtocolVersion('1.0');
        $this->assertSame('1.0', $result->getProtocolVersion());
        $this->assertSame('1.1', $stub->getProtocolVersion());
    }

    /**
     * @covers \Kusabi\Http\Message::withoutHeader
     */
    public function testWithoutHeader()
    {
        $stub = $this->createInstance();
        $stub->addHeader('foo', 'bar');
        $stub->addHeader('baz', 'boss');
        $result = $stub->withoutHeader('foo');
        $this->assertFalse($result->hasHeader('foo'));
        $this->assertEquals(['baz' => 'boss'], $result->getHeaders());
        $this->assertTrue($stub->hasHeader('foo'));
        $this->assertEquals(['baz' => 'boss'], $result->getHeaders());
    }

    /**
     * @param mixed $key
     *
     * @dataProvider providesInvalidHeaderKeys
     *
     * @covers       \Kusabi\Http\Message::withoutHeader
     */
    public function testWithoutHeaderInvalidKeys($key)
    {
        $this->expectException(InvalidArgumentException::class);
        $stub = $this->createInstance();
        $stub->addHeader('foo', 'bar');
        $stub->addHeader('baz', 'boss');
        $stub->withoutHeader($key);
    }
}
