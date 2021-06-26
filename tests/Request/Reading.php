<?php

namespace Kusabi\Http\Tests\Request;

use Kusabi\Http\Request;
use Kusabi\Http\Tests\RequestTestCase;

class Reading extends RequestTestCase
{
    /**
     * @param $method
     *
     * @dataProvider providesValidRequestMethods
     *
     * @covers \Kusabi\Http\Request::getMethod
     */
    public function testGetMethod($method)
    {
        $request = new Request($method, 'http://www.example.com');
        $this->assertSame($method, $request->getMethod());
    }

    /**
     * @param string $uri
     * @param string $requestTarget
     *
     * @dataProvider providesUriToRequestTargets
     *
     * @covers \Kusabi\Http\Request::getRequestTarget
     */
    public function testRequestTargetComesFromPathWhenNoneIsSet($uri, $requestTarget)
    {
        $request = new Request('GET', $uri);
        $this->assertSame($requestTarget, $request->getRequestTarget());
    }
}
