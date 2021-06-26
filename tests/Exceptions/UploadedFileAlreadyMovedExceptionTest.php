<?php

namespace Kusabi\Http\Tests\Exceptions;

use Kusabi\Http\Exceptions\UploadedFileAlreadyMovedException;
use Kusabi\Http\Tests\TestCase;

class UploadedFileAlreadyMovedExceptionTest extends TestCase
{
    /**
     * @covers \Kusabi\Http\Exceptions\UploadedFileAlreadyMovedException::__construct
     */
    public function testGetMessages()
    {
        $exception = new UploadedFileAlreadyMovedException();
        $this->assertSame('', $exception->getMessage());
    }
}
