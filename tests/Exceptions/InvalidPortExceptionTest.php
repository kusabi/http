<?php

namespace Kusabi\Http\Tests\Exceptions;

use Kusabi\Http\Exceptions\InvalidPortException;
use Kusabi\Http\Tests\TestCase;

class InvalidPortExceptionTest extends TestCase
{
    /**
     * @covers \Kusabi\Http\Exceptions\InvalidPortException::__construct
     */
    public function testGetMessages()
    {
        $exception = new InvalidPortException('TEST');
        $this->assertSame("Port 'TEST' is not within a valid UDP/TCP range", $exception->getMessage());
    }
}
