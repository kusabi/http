<?php

namespace Kusabi\Http\Tests\Exceptions;

use Kusabi\Http\Exceptions\InvalidHeaderKeyException;
use Kusabi\Http\Tests\TestCase;
use stdClass;

class InvalidHeaderKeyExceptionTest extends TestCase
{
    public function providesKeyToMessages(): array
    {
        return [
            ['', 'Headers must be a non-empty string but <empty string> was provided'],
            ['test', 'Headers must be a non-empty string but <string> was provided'],
            [1, 'Headers must be a non-empty string but <integer> was provided'],
            [false, 'Headers must be a non-empty string but <boolean> was provided'],
            [true, 'Headers must be a non-empty string but <boolean> was provided'],
            [null, 'Headers must be a non-empty string but <NULL> was provided'],
            [[], 'Headers must be a non-empty string but <array> was provided'],
            [new stdClass(), 'Headers must be a non-empty string but <stdClass> was provided'],
        ];
    }

    /**
     * @dataProvider providesKeyToMessages
     *
     * @covers \Kusabi\Http\Exceptions\InvalidHeaderKeyException::__construct
     */
    public function testKeyMessages($key, string $message)
    {
        $exception = new InvalidHeaderKeyException($key);
        $this->assertSame($message, $exception->getMessage());
    }
}
