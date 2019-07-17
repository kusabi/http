<?php

namespace Tests;

use InvalidArgumentException;
use Kusabi\Http\AbstractMessage;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;

class MessageHeadersTest extends TestCase
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

    public function testGetHeadersReturnsExactHeaders()
    {
        $headers = ['FoO' => 'bAr', 'BAZ' => 'FoO'];
        $stub = $this->createInstance($headers);
        $this->assertSame($headers, $stub->getHeaders());
    }

    public function testSetHeadersOverwritesExistingHeaders()
    {
        $stub = $this->createInstance(['FoO' => 'bAr', 'BAZ' => 'FoO']);
        $stub->setHeaders(['a' => 'b', 'C' => 'D']);
        $this->assertSame(['a' => 'b', 'C' => 'D'], $stub->getHeaders());
    }

    public function testSetHeadersThrowsExceptionForInvalidTypes()
    {
        $this->expectException(InvalidArgumentException::class);
        $stub = $this->createInstance();
        $stub->setHeaders([
            '' => 'test'
        ]);
    }

    public function testSetHeaderOverwritesExistingHeader()
    {
        $stub = $this->createInstance(['FoO' => 'bAr', 'BAZ' => 'FoO']);
        $stub->setHeader('FOO', 'BAZ');
        $this->assertEquals(['FOO' => 'BAZ', 'BAZ' => 'FoO'], $stub->getHeaders());
    }

    /**
     * @param mixed $key
     * @dataProvider invalidHeaderKeyProvider()
     */
    public function testSetHeaderThrowsExceptionForInvalidHeaderKey($key)
    {
        $this->expectException(InvalidArgumentException::class);
        $stub = $this->createInstance();
        $stub->setHeader($key, 'foo');
    }

    public function testWithHeaders()
    {
        $headers = ['FoO' => 'bAr', 'BAZ' => 'FoO'];
        $stub = $this->createInstance();
        $instance = $stub->withHeaders($headers);
        $this->assertSame($headers, $instance->getHeaders());
        $this->assertNotSame($instance, $stub);
    }

    public function testWithHeadersReturnsSameInstanceIfUnchanged()
    {
        $headers = ['FoO' => 'bAr', 'BAZ' => 'FoO'];
        $stub = $this->createInstance($headers);
        $new = $stub->withHeaders($headers);
        $this->assertSame($headers, $new->getHeaders());
        $this->assertSame($new, $stub);
    }

    public function testWithHeadersThrowsExceptionForInvalidHeaderKey()
    {
        $this->expectException(InvalidArgumentException::class);
        $stub = $this->createInstance();
        $stub->setHeaders(['' => 'foo']);
    }

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
     * @param mixed $key
     * @dataProvider invalidHeaderKeyProvider()
     */
    public function testWithHeaderThrowsExceptionForInvalidHeaderKey($key)
    {
        $this->expectException(InvalidArgumentException::class);
        $stub = $this->createInstance();
        $stub->withHeader($key, 'foo');
    }

    public function testHasHeaderIsCaseInsensitive()
    {
        $stub = $this->createInstance(['FoO' => 'bAr', 'BAZ' => 'FoO']);
        $this->assertTrue($stub->hasHeader('foo'));
        $this->assertTrue($stub->hasHeader('FoO'));
        $this->assertTrue($stub->hasHeader('FOO'));
    }

    public function testGetHeaderIsCaseInsensitive()
    {
        $stub = $this->createInstance(['FoO' => 'bAr', 'BAZ' => 'FoO']);
        $this->assertSame(['bAr'], $stub->getHeader('foo'));
        $this->assertSame(['bAr'], $stub->getHeader('FoO'));
        $this->assertSame(['bAr'], $stub->getHeader('FOO'));
    }

    public function testGetHeaderReturnsEmptyArrayIfNotFound()
    {
        $stub = $this->createInstance(['foo' => 'bar']);
        $this->assertSame([], $stub->getHeader('not-found'));
    }

    public function testSetHeaderIsCaseInsensitive()
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

    public function testGetHeaderLineReturnsCommaSeparatedList()
    {
        $stub = $this->createInstance(['h' => ['a', 'b', 'c']]);
        $this->assertEquals('a,b,c', $stub->getHeaderLine('h'));

        $stub = $this->createInstance(['h' => 'a']);
        $this->assertEquals('a', $stub->getHeaderLine('h'));

        $this->assertEquals('', $stub->getHeaderLine('not-found'));
    }

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
     * @dataProvider invalidHeaderKeyProvider()
     */
    public function testAddHeaderThrowsExceptionForInvalidHeaderKey($key)
    {
        $this->expectException(InvalidArgumentException::class);
        $stub = $this->createInstance();
        $stub->addHeader($key, 'foo');
    }

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
     * @dataProvider invalidHeaderKeyProvider()
     */
    public function testWithAddedHeaderThrowsExceptionForInvalidHeaderKey($key)
    {
        $this->expectException(InvalidArgumentException::class);
        $stub = $this->createInstance();
        $stub->withAddedHeader($key, 'foo');
    }

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
     * @dataProvider invalidHeaderKeyProvider()
     */
    public function testRemoveHeaderInvalidKeys($key)
    {
        $this->expectException(InvalidArgumentException::class);
        $stub = $this->createInstance();
        $stub->addHeader('foo', 'bar');
        $stub->addHeader('baz', 'boss');
        $stub->removeHeader($key);
    }

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
     * @dataProvider invalidHeaderKeyProvider()
     */
    public function testWithoutHeaderInvalidKeys($key)
    {
        $this->expectException(InvalidArgumentException::class);
        $stub = $this->createInstance();
        $stub->addHeader('foo', 'bar');
        $stub->addHeader('baz', 'boss');
        $stub->withoutHeader($key);
    }

    /**
     * Data provider of invalid header keys
     *
     * @return array
     */
    public function invalidHeaderKeyProvider()
    {
        return [
            [''],
            [[]],
            [new stdClass()]
        ];
    }
}
