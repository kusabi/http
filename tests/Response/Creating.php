<?php

namespace Kusabi\Http\Tests\Response;

use InvalidArgumentException;
use Kusabi\Http\Response;
use Kusabi\Http\Tests\ResponseTestCase;

class Creating extends ResponseTestCase
{
    /**
     * @param int $code
     * @param string $reason
     *
     * @dataProvider providesValidStatusCodes
     *
     * @covers \Kusabi\Http\Response::__construct
     */
    public function testDefaultResponseReason(int $code, string $reason)
    {
        $response = new Response($code);
        $this->assertSame($reason, $response->getReasonPhrase());
    }

    /**
     * @covers \Kusabi\Http\Response::__construct
     */
    public function testInvalidStatusCodeThrows()
    {
        $this->expectException(InvalidArgumentException::class);
        new Response(99);
    }

    /**
     * @covers \Kusabi\Http\Response::__construct
     */
    public function testOverrideReasonPhrase()
    {
        $response = new Response(200, [], null, '1.1', 'hello');
        $this->assertSame('hello', $response->getReasonPhrase());
    }
}
