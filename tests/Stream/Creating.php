<?php

namespace Kusabi\Http\Tests\Stream;

use Kusabi\Http\Stream;
use Kusabi\Http\StreamFactory;
use Kusabi\Http\Tests\TestCase;

class Creating extends TestCase
{
    /**
     * @covers \Kusabi\Http\Stream::__construct
     */
    public function testCreateStreamFromResource()
    {
        $resource = fopen('php://temp', 'w+');
        $stream = new Stream($resource);
        $this->assertInstanceOf(Stream::class, $stream);
    }
}
