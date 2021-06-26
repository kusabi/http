<?php

namespace Kusabi\Http\Tests\Exceptions;

use Kusabi\Http\Exceptions\InvalidUriException;
use Kusabi\Http\Tests\TestCase;

class InvalidUriExceptionTest extends TestCase
{
    /**
     * @covers \Kusabi\Http\Exceptions\InvalidUriException::__construct
     */
    public function testGetMessages()
    {
        $exception = new InvalidUriException('TEST');
        $this->assertSame("The uri 'TEST' is not valid", $exception->getMessage());
    }
}
