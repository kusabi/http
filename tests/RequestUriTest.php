<?php

namespace Tests;

use Kusabi\Http\Request;
use Kusabi\Uri\Uri;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UriInterface;

class RequestUriTest extends TestCase
{
    public function testUriIsSetFromConstructor()
    {
        $request = new Request('GET', new Uri('http://www.example.com'));
        $this->assertInstanceOf(Uri::class, $request->getUri());
        $this->assertInstanceOf(UriInterface::class, $request->getUri());
    }

    public function testUriIsSetFromConstructorAsString()
    {
        $request = new Request('GET', 'http://www.example.com');
        $this->assertInstanceOf(Uri::class, $request->getUri());
        $this->assertInstanceOf(UriInterface::class, $request->getUri());
    }

    public function testSetUriChangesTheUri()
    {
        $request = new Request('GET', 'http://www.example.com');
        $request->setUri(new Uri('http://www2.changed.org'));
        $this->assertInstanceOf(Uri::class, $request->getUri());
        $this->assertInstanceOf(UriInterface::class, $request->getUri());
        $this->assertSame('http://www2.changed.org', (string) $request->getUri());
    }

    public function testWithUriChangesTheUri()
    {
        $request = new Request('GET', 'http://www.example.com');
        $request = $request->withUri(new Uri('http://www2.changed.org'));
        $this->assertInstanceOf(Uri::class, $request->getUri());
        $this->assertInstanceOf(UriInterface::class, $request->getUri());
        $this->assertSame('http://www2.changed.org', (string) $request->getUri());
    }

    public function testWithUriReturnsNewInstance()
    {
        $request = new Request('GET', 'http://www.example.com');
        $new = $request->withUri(new Uri('http://www2.changed.org'));
        $this->assertNotSame($request, $new);
    }

    public function testSetHeaderUpdatesHostAccordingToPsrRules()
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
