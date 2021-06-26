<?php

namespace Kusabi\Http\Tests\Exceptions;

use Kusabi\Http\Exceptions\InvalidHttpMethodException;
use Kusabi\Http\Tests\TestCase;

class InvalidHttpMethodExceptionTest extends TestCase
{
    /**
     * @covers \Kusabi\Http\Exceptions\InvalidHttpMethodException::__construct
     */
    public function testGetMessages()
    {
        $exception = new InvalidHttpMethodException('TEST');
        $this->assertSame("Invalid HTTP method 'TEST' was provided", $exception->getMessage());
    }
}
