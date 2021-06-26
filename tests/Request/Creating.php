<?php

namespace Kusabi\Http\Tests\Request;

use InvalidArgumentException;
use Kusabi\Http\Request;
use Kusabi\Http\Tests\RequestTestCase;
use Kusabi\Http\Uri;
use Psr\Http\Message\UriInterface;

class Creating extends RequestTestCase
{
    /**
     * @param $method
     *
     * @dataProvider providesInvalidRequestMethod
     *
     * @covers \Kusabi\Http\Request::__construct
     */
    public function testConstructorThrowsExceptionWHenInvalidMethod($method)
    {
        $this->expectException(InvalidArgumentException::class);
        new Request($method, 'http://www.example.com');
    }

    /**
     * @covers \Kusabi\Http\Request::__construct
     * @covers \Kusabi\Http\Request::getUri
     */
    public function testUriIsSetFromConstructor()
    {
        $request = new Request('GET', new Uri('http://www.example.com'));
        $this->assertInstanceOf(Uri::class, $request->getUri());
        $this->assertInstanceOf(UriInterface::class, $request->getUri());
    }

    /**
     * @covers \Kusabi\Http\Request::__construct
     * @covers \Kusabi\Http\Request::getUri
     */
    public function testUriIsSetFromConstructorAsString()
    {
        $request = new Request('GET', 'http://www.example.com');
        $this->assertInstanceOf(Uri::class, $request->getUri());
        $this->assertInstanceOf(UriInterface::class, $request->getUri());
    }
}
