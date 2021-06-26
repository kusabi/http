<?php

namespace Kusabi\Http\Tests\Response;

use InvalidArgumentException;
use Kusabi\Http\Response;
use Kusabi\Http\Tests\ResponseTestCase;

class Modifying extends ResponseTestCase
{
    /**
     * @covers \Kusabi\Http\Response::setReasonPhrase
     */
    public function testSetReasonPhraseOverrideReasonPhrase()
    {
        $response = new Response();
        $response->setReasonPhrase('hello');
        $this->assertSame('hello', $response->getReasonPhrase());
    }

    /**
     * @param int $code
     *
     * @dataProvider providesValidStatusCodes
     *
     * @covers       \Kusabi\Http\Response::setStatus
     */
    public function testSetStatus(int $code)
    {
        $response = new Response(200);
        $response->setStatus($code);
        $this->assertSame($code, $response->getStatusCode());
    }

    /**
     * @param int $code
     *
     * @dataProvider providesValidStatusCodes
     *
     * @covers       \Kusabi\Http\Response::setStatusCode
     */
    public function testSetStatusCode(int $code)
    {
        $response = new Response(200);
        $response->setStatusCode($code);
        $this->assertSame($code, $response->getStatusCode());
    }

    /**
     * @covers \Kusabi\Http\Response::setStatusCode
     */
    public function testSetStatusCodeThrowsException()
    {
        $this->expectException(InvalidArgumentException::class);
        $response = new Response(200);
        $response->setStatusCode(99);
    }

    /**
     * @covers \Kusabi\Http\Response::setStatus
     */
    public function testSetStatusOverrideReasonPhrase()
    {
        $response = new Response();
        $response->setStatus(200, 'test');
        $this->assertSame('test', $response->getReasonPhrase());
    }

    /**
     * @covers \Kusabi\Http\Response::setStatus
     */
    public function testSetStatusThrowsException()
    {
        $this->expectException(InvalidArgumentException::class);
        $response = new Response(200);
        $response->setStatus(99);
    }

    /**
     * @covers \Kusabi\Http\Response::withReasonPhrase
     */
    public function testWithReasonPhraseOverrideReasonPhrase()
    {
        $response = new Response();
        $response = $response->withReasonPhrase('test');
        $this->assertSame('test', $response->getReasonPhrase());
    }

    /**
     * @covers \Kusabi\Http\Response::withReasonPhrase
     */
    public function testWithReasonPhraseReturnsNewInstanceForNewReason()
    {
        $response = new Response();
        $new = $response->withReasonPhrase('test');
        $this->assertNotSame($response, $new);
        $this->assertSame('test', $new->getReasonPhrase());
    }

    /**
     * @covers \Kusabi\Http\Response::withReasonPhrase
     */
    public function testWithReasonPhraseReturnsSameInstanceForSameReason()
    {
        $response = new Response();
        $response->setReasonPhrase('test');
        $new = $response->withReasonPhrase('test');
        $this->assertSame($response, $new);
        $this->assertSame('test', $response->getReasonPhrase());
    }

    /**
     * @covers \Kusabi\Http\Response::withStatusCode
     */
    public function testWithStatusCode()
    {
        $response = new Response(200);
        $copy = $response->withStatusCode(300);
        $this->assertNotSame($copy, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(300, $copy->getStatusCode());
    }

    /**
     * @covers \Kusabi\Http\Response::withStatusCode
     */
    public function testWithStatusCodeWithSameValidCodeReturnsSameInstance()
    {
        $response = new Response(200);
        $new = $response->withStatusCode(200);
        $this->assertSame($new, $response);
        $this->assertSame(200, $new->getStatusCode());
    }

    /**
     * @covers \Kusabi\Http\Response::withStatus
     */
    public function testWithStatusOverrideReasonPhrase()
    {
        $response = new Response();
        $response = $response->withStatus(200, 'hello');
        $this->assertSame('hello', $response->getReasonPhrase());
    }

    /**
     * @covers \Kusabi\Http\Response::withStatus
     */
    public function testWithStatusWithNewValidCodeReturnsNewInstance()
    {
        $response = new Response(200);
        $copy = $response->withStatus(300);
        $this->assertNotSame($copy, $response);
        $this->assertSame(300, $copy->getStatusCode());
        $this->assertSame(200, $response->getStatusCode());
    }

    /**
     * @covers \Kusabi\Http\Response::withStatus
     */
    public function testWithStatusWithSameValidCodeReturnsSameInstance()
    {
        $response = new Response(200);
        $new = $response->withStatus(200);
        $this->assertSame($new, $response);
        $this->assertSame(200, $new->getStatusCode());
    }
}
