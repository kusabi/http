<?php

namespace Kusabi\Http\Tests\Exceptions;

use Kusabi\Http\Exceptions\InvalidHttpStatusCodeException;
use Kusabi\Http\Tests\TestCase;

class InvalidHttpStatusCodeExceptionTest extends TestCase
{
    /**
     * @covers \Kusabi\Http\Exceptions\InvalidHttpStatusCodeException::__construct
     */
    public function testGetMessages()
    {
        $exception = new InvalidHttpStatusCodeException('TEST');
        $this->assertSame("Invalid HTTP response status code 'TEST' was provided", $exception->getMessage());
    }
}
