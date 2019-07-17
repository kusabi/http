<?php

namespace Tests;

use Kusabi\Http\Request;
use PHPUnit\Framework\TestCase;

class RequestTargetTest extends TestCase
{
    /**
     * @param string $uri
     * @param string $requestTarget
     *
     * @dataProvider uriToRequestTargetProvider
     */
    public function testRequestTargetComesFromPathWhenNoneIsSet($uri, $requestTarget)
    {
        $request = new Request('GET', $uri);
        $this->assertSame($requestTarget, $request->getRequestTarget());
    }

    public function testSetRequestTargetOverridesTarget()
    {
        $request = new Request('GET', 'http://www.example.com');
        $request->setRequestTarget('testing');
        $this->assertSame('testing', $request->getRequestTarget());
    }

    public function testWithRequestTargetOverridesTarget()
    {
        $request = new Request('GET', 'http://www.example.com');
        $request = $request->withRequestTarget('testing');
        $this->assertSame('testing', $request->getRequestTarget());
    }

    public function testWithRequestReturnsNewInstance()
    {
        $request = new Request('GET', 'http://www.example.com');
        $new = $request->withRequestTarget('testing');
        $this->assertNotSame($request, $new);
    }

    public function testWithRequestReturnsSameInstanceIfNoChange()
    {
        $request = new Request('GET', 'http://www.example.com');
        $request->setRequestTarget('testing');
        $new = $request->withRequestTarget('testing');
        $this->assertSame($request, $new);
    }

    public function uriToRequestTargetProvider()
    {
        return [
            ['http://www.example.com', '/'],
            ['http://www.example.com/', '/'],
            ['http://www.example.com/some', '/some'],
            ['http://www.example.com/some/', '/some/'],
            ['http://www.example.com/some/path', '/some/path'],
            ['http://www.example.com/some/path?a=b&c=d', '/some/path?a=b&c=d'],
        ];
    }
}
