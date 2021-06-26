<?php

namespace Kusabi\Http\Tests\Exceptions;

use Kusabi\Http\Exceptions\InvalidUploadedFileStatusException;
use Kusabi\Http\Tests\TestCase;

class InvalidUploadedFileStatusExceptionTest extends TestCase
{
    /**
     * @covers \Kusabi\Http\Exceptions\InvalidUploadedFileStatusException::__construct
     */
    public function testGetMessages()
    {
        $exception = new InvalidUploadedFileStatusException('TEST');
        $this->assertSame("Invalid status 'TEST' for an uploaded file. Value must be one of PHPs UPLOAD_ERR_XXX constants", $exception->getMessage());
    }
}
