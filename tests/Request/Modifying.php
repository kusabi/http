<?php

namespace Kusabi\Http\Tests\Request;

use InvalidArgumentException;
use Kusabi\Http\Request;
use Kusabi\Http\Tests\RequestTestCase;
use Kusabi\Http\Uri;
use Psr\Http\Message\UriInterface;

class Modifying extends RequestTestCase
{
    /**
     * @param $method
     *
     * @dataProvider providesValidRequestMethods
     *
     * @covers       \Kusabi\Http\Request::setMethod
     */
    public function testSetMethod($method)
    {
        $request = new Request('GET', new Uri('http://www.example.com'));
        $request->setMethod($method);
        $this->assertSame($method, $request->getMethod());
    }

    /**
     * @param $method
     *
     * @dataProvider providesInvalidRequestMethod
     *
     * @covers       \Kusabi\Http\Request::setMethod
     */
    public function testSetMethodThrowsExceptionForInvalidMethods($method)
    {
        $this->expectException(InvalidArgumentException::class);
        $request = new Request('GET', new Uri('http://www.example.com'));
        $request->setMethod($method);
    }

    /**
     * @covers \Kusabi\Http\Request::setRequestTarget
     * @covers \Kusabi\Http\Request::getRequestTarget
     */
    public function testSetRequestTargetOverridesTarget()
    {
        $request = new Request('GET', 'http://www.example.com');
        $request->setRequestTarget('testing');
        $this->assertSame('testing', $request->getRequestTarget());
    }

    /**
     * @param $method
     *
     * @dataProvider providesValidRequestMethods
     *
     * @covers       \Kusabi\Http\Request::withMethod
     */
    public function testWithMethod($method)
    {
        $request = new Request('GET', new Uri('http://www.example.com'));
        $new = $request->withMethod($method);
        $this->assertSame('GET', $request->getMethod());
        $this->assertSame($method, $new->getMethod());
    }

    /**
     * @covers \Kusabi\Http\Request::withMethod
     */
    public function testWithMethodReturnsSameInstanceWhenNoChange()
    {
        $request = new Request('GET', new Uri('http://www.example.com'));
        $new = $request->withMethod('GET');
        $this->assertSame($request, $new);
        $this->assertSame('GET', $new->getMethod());
    }

    /**
     * @param $method
     *
     * @dataProvider providesInvalidRequestMethod
     *
     * @covers       \Kusabi\Http\Request::withMethod
     */
    public function testWithMethodThrowsExceptionForInvalidMethods($method)
    {
        $this->expectException(InvalidArgumentException::class);
        $request = new Request('GET', new Uri('http://www.example.com'));
        $request->withMethod($method);
    }

    /**
     * @covers \Kusabi\Http\Request::withRequestTarget
     */
    public function testWithRequestReturnsNewInstance()
    {
        $request = new Request('GET', 'http://www.example.com');
        $new = $request->withRequestTarget('testing');
        $this->assertNotSame($request, $new);
    }

    /**
     * @covers \Kusabi\Http\Request::withRequestTarget
     */
    public function testWithRequestReturnsSameInstanceIfNoChange()
    {
        $request = new Request('GET', 'http://www.example.com');
        $request->setRequestTarget('testing');
        $new = $request->withRequestTarget('testing');
        $this->assertSame($request, $new);
    }

    /**
     * @covers \Kusabi\Http\Request::withRequestTarget
     */
    public function testWithRequestTargetOverridesTarget()
    {
        $request = new Request('GET', 'http://www.example.com');
        $copy = $request->withRequestTarget('testing');
        $this->assertSame('/', $request->getRequestTarget());
        $this->assertSame('testing', $copy->getRequestTarget());
    }

    /**
     * @covers \Kusabi\Http\Request::setUri
     */
    public function testSetUriChangesTheUri()
    {
        $request = new Request('GET', 'http://www.example.com');
        $request->setUri(new Uri('http://www2.changed.org'));
        $this->assertInstanceOf(Uri::class, $request->getUri());
        $this->assertInstanceOf(UriInterface::class, $request->getUri());
        $this->assertSame('http://www2.changed.org', (string) $request->getUri());
    }

    /**
     * @covers \Kusabi\Http\Request::withUri
     */
    public function testWithUriChangesTheUri()
    {
        $request = new Request('GET', 'http://www.example.com');
        $copy = $request->withUri(new Uri('http://www2.changed.org'));
        $this->assertInstanceOf(Uri::class, $request->getUri());
        $this->assertInstanceOf(UriInterface::class, $request->getUri());
        $this->assertSame('http://www.example.com', (string) $request->getUri());
        $this->assertInstanceOf(Uri::class, $copy->getUri());
        $this->assertInstanceOf(UriInterface::class, $copy->getUri());
        $this->assertSame('http://www2.changed.org', (string) $copy->getUri());
    }

    /**
     * @covers \Kusabi\Http\Request::setUri
     */
    public function testSetUriUpdatesHostAccordingToPsrRules()
    {
        // Preserve is set to false...
        // If the Host header is missing or empty, and the new URI contains a host component,
        // this method MUST update the Host header in the returned request.
        $request = new Request('GET', 'http://www.example.com');
        $request->removeHeader('Host');
        $request->setUri(new Uri('http://www.example.com'));
        $this->assertSame('www.example.com', $request->getHeaderLine('Host'));

        // Preserve is set to false...
        // If the Host header is missing or empty, and the new URI does not contain a host component,
        // this method MUST NOT update the Host header in the returned request.
        $request = new Request('GET', 'http://www.example.com');
        $request->setUri(new Uri('/lol'));
        $this->assertSame('www.example.com', $request->getHeaderLine('Host'));

        // Preserve is set to false...
        // If the Host header is missing or empty, and the new URI does not contain a host component,
        // this method MUST NOT update the Host header in the returned request.
        $request = new Request('GET', 'http://www.example.com');
        $request->removeHeader('Host');
        $request->setUri(new Uri('/lol'));
        $this->assertSame('', $request->getHeaderLine('Host'));

        // Preserve is set to false...
        // If a Host header is present and non-empty, this method MUST update the Host header in the returned request.
        $request = new Request('GET', 'http://www.example.com');
        $request->setUri(new Uri('http://www.test.com'));
        $this->assertSame('www.test.com', $request->getHeaderLine('Host'));

        // Preserve is set to true...
        // If the Host header is missing or empty, and the new URI contains a host component,
        // this method MUST update the Host header in the returned request.
        $request = new Request('GET', 'http://www.example.com');
        $request->removeHeader('Host');
        $request->setUri(new Uri('http://www.example.com'), true);
        $this->assertSame('www.example.com', $request->getHeaderLine('Host'));

        // Preserve is set to true...
        // If the Host header is missing or empty, and the new URI does not contain a host component,
        // this method MUST NOT update the Host header in the returned request.
        $request = new Request('GET', 'http://www.example.com');
        $request->removeHeader('Host');
        $request->setUri(new Uri('/lol'), true);
        $this->assertSame('', $request->getHeaderLine('Host'));

        // Preserve is set to true...
        // If a Host header is present and non-empty, this method MUST NOT update the Host header in the returned request.
        $request = new Request('GET', 'http://www.example.com');
        $request->setUri(new Uri('http://www.test.com'), true);
        $this->assertSame('www.example.com', $request->getHeaderLine('Host'));
    }
}
