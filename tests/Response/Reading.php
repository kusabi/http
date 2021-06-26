<?php

namespace Kusabi\Http\Tests\Response;

use Kusabi\Http\Response;
use Kusabi\Http\Tests\ResponseTestCase;

class Reading extends ResponseTestCase
{
    /**
     * @param int $code
     *
     * @dataProvider providesValidStatusCodes
     *
     * @covers       \Kusabi\Http\Response::getStatusCode
     */
    public function testValidStatusCodes(int $code)
    {
        $response = new Response($code);
        $this->assertSame($code, $response->getStatusCode());
    }

    /**
     * @param int $code
     * @param string $reason
     *
     * @dataProvider providesValidStatusCodes
     *
     * @covers \Kusabi\Http\Response::getReasonPhrase
     */
    public function testGetReasonPhrase(int $code, string $reason)
    {
        $response = new Response($code);
        $this->assertSame($reason, $response->getReasonPhrase());
    }
}
