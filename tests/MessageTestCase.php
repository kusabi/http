<?php

namespace Kusabi\Http\Tests;

use Kusabi\Http\Message;
use PHPUnit\Framework\MockObject\MockObject;
use stdClass;

class MessageTestCase extends TestCase
{
    /**
     * Data provider of invalid header keys
     *
     * @return array
     */
    public function providesInvalidHeaderKeys(): array
    {
        return [
            [''],
            [[]],
            [new stdClass()]
        ];
    }

    /**
     * Create a stub implementation of the abstract class
     *
     * @param array $headers
     * @param null $body
     * @param string $protocol
     *
     * @return Message|MockObject
     */
    protected function createInstance(array $headers = [], $body = null, string $protocol = '1.1')
    {
        return $this->getMockForAbstractClass(Message::class, [
            $headers,
            $body,
            $protocol
        ]);
    }
}
