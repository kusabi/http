<?php

namespace Kusabi\Http\Tests\Message;

use Kusabi\Http\Tests\MessageTestCase;

class Reading extends MessageTestCase
{
    /**
     * @covers \Kusabi\Http\Message::getHeader
     */
    public function testGetHeaderIsCaseInsensitive()
    {
        $stub = $this->createInstance(['FoO' => 'bAr', 'BAZ' => 'FoO']);
        $this->assertSame(['bAr'], $stub->getHeader('foo'));
        $this->assertSame(['bAr'], $stub->getHeader('FoO'));
        $this->assertSame(['bAr'], $stub->getHeader('FOO'));
    }

    /**
     * @covers \Kusabi\Http\Message::getHeaderLine
     */
    public function testGetHeaderLineReturnsCommaSeparatedList()
    {
        $stub = $this->createInstance(['h' => ['a', 'b', 'c']]);
        $this->assertEquals('a,b,c', $stub->getHeaderLine('h'));

        $stub = $this->createInstance(['h' => 'a']);
        $this->assertEquals('a', $stub->getHeaderLine('h'));

        $this->assertEquals('', $stub->getHeaderLine('not-found'));
    }

    /**
     * @covers \Kusabi\Http\Message::getHeader
     */
    public function testGetHeaderReturnsEmptyArrayIfNotFound()
    {
        $stub = $this->createInstance(['foo' => 'bar']);
        $this->assertSame([], $stub->getHeader('not-found'));
    }

    /**
     * @covers \Kusabi\Http\Message::getHeaders
     */
    public function testGetHeadersReturnsExactHeaders()
    {
        $headers = ['FoO' => 'bAr', 'BAZ' => 'FoO'];
        $stub = $this->createInstance($headers);
        $this->assertSame($headers, $stub->getHeaders());
    }

    /**
     * @covers \Kusabi\Http\Message::getProtocolVersion
     */
    public function testGetProtocolVersion()
    {
        $stub = $this->createInstance();
        $this->assertSame('1.1', $stub->getProtocolVersion());
    }

    /**
     * @covers \Kusabi\Http\Message::getProtocolVersion
     */
    public function testGetProtocolVersionReturnsProtocol()
    {
        $stub = $this->createInstance([], null, '1.0');
        $this->assertSame('1.0', $stub->getProtocolVersion());
    }

    /**
     * @covers \Kusabi\Http\Message::hasHeader
     */
    public function testHasHeaderIsCaseInsensitive()
    {
        $stub = $this->createInstance(['FoO' => 'bAr', 'BAZ' => 'FoO']);
        $this->assertTrue($stub->hasHeader('foo'));
        $this->assertTrue($stub->hasHeader('FoO'));
        $this->assertTrue($stub->hasHeader('FOO'));
    }
}
