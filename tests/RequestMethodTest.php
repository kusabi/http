<?php

namespace Tests;

use InvalidArgumentException;
use Kusabi\Http\Request;
use Kusabi\Uri\Uri;
use PHPUnit\Framework\TestCase;

class RequestMethodTest extends TestCase
{
    /**
     * @param $method
     *
     * @dataProvider validRequestMethodProvider
     */
    public function testConstructorSetsRequestMethod($method)
    {
        $request = new Request($method, 'http://www.example.com');
        $this->assertSame($method, $request->getMethod());
    }

    /**
     * @param $method
     *
     * @dataProvider invalidRequestMethodProvider
     */
    public function testConstructorThrowsExceptionWHenInvalidMethod($method)
    {
        $this->expectException(InvalidArgumentException::class);
        new Request($method, 'http://www.example.com');
    }

    /**
     * @param $method
     *
     * @dataProvider validRequestMethodProvider
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
     * @dataProvider invalidRequestMethodProvider
     */
    public function testSetMethodThrowsExceptionForInvalidMethods($method)
    {
        $this->expectException(InvalidArgumentException::class);
        $request = new Request('GET', new Uri('http://www.example.com'));
        $request->setMethod($method);
    }

    /**
     * @param $method
     *
     * @dataProvider validRequestMethodProvider
     */
    public function testWithMethod($method)
    {
        $request = new Request('GET', new Uri('http://www.example.com'));
        $new = $request->withMethod($method);
        $this->assertSame($method, $new->getMethod());
    }

    /**
     * @param $method
     *
     * @dataProvider invalidRequestMethodProvider
     */
    public function testWithMethodThrowsExceptionForInvalidMethods($method)
    {
        $this->expectException(InvalidArgumentException::class);
        $request = new Request('GET', new Uri('http://www.example.com'));
        $request->withMethod($method);
    }

    public function testWithMethodReturnsClone()
    {
        $request = new Request('GET', new Uri('http://www.example.com'));
        $new = $request->withMethod('POST');
        $this->assertNotSame($request, $new);
        $this->assertSame('POST', $new->getMethod());
    }

    public function testWithMethodReturnsSameINstanceWhenNoChange()
    {
        $request = new Request('GET', new Uri('http://www.example.com'));
        $new = $request->withMethod('GET');
        $this->assertSame($request, $new);
        $this->assertSame('GET', $new->getMethod());
    }

    public function validRequestMethodProvider()
    {
        return [
            ['get'],
            ['GET'],
            ['head'],
            ['HEAD'],
            ['post'],
            ['POST'],
            ['put'],
            ['PUT'],
            ['delete'],
            ['DELETE'],
            ['connect'],
            ['CONNECT'],
            ['options'],
            ['OPTIONS'],
            ['trace'],
            ['TRACE'],
            ['patch'],
            ['PATCH']
        ];
    }

    public function invalidRequestMethodProvider()
    {
        return [
            ['option'],
            ['not-real'],
            [''],
            [' GET'],
            ['GET '],
        ];
    }
}
