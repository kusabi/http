<?php

namespace Tests;

use InvalidArgumentException;
use Kusabi\Http\Response;
use PHPUnit\Framework\TestCase;

class ResponseStatusTest extends TestCase
{
    /**
     * @param int $code
     *
     * @dataProvider validStatusCodeProvider
     */
    public function testValidStatusCodes($code)
    {
        $response = new Response($code);
        $this->assertSame($code, $response->getStatusCode());
    }

    public function testInvalidStatusCodeThrows()
    {
        $this->expectException(InvalidArgumentException::class);
        new Response(99);
    }

    /**
     * @param int $code
     *
     * @dataProvider validStatusCodeProvider
     */
    public function testSetStatusCodeWithValidCode($code)
    {
        $response = new Response(200);
        $response->setStatusCode($code);
        $this->assertSame($code, $response->getStatusCode());
    }

    /**
     * @param int $code
     *
     * @dataProvider validStatusCodeProvider
     */
    public function testSetStatusWithValidCode($code)
    {
        $response = new Response(200);
        $response->setStatus($code);
        $this->assertSame($code, $response->getStatusCode());
    }

    public function testWithStatusCodeWithNewValidCodeReturnsNewInstance()
    {
        $response = new Response(200);
        $new = $response->withStatusCode(300);
        $this->assertNotSame($new, $response);
        $this->assertSame(300, $new->getStatusCode());
    }

    public function testWithStatusWithNewValidCodeReturnsNewInstance()
    {
        $response = new Response(200);
        $new = $response->withStatus(300);
        $this->assertNotSame($new, $response);
        $this->assertSame(300, $new->getStatusCode());
    }

    public function testWithStatusCodeWithSameValidCodeReturnsSameInstance()
    {
        $response = new Response(200);
        $new = $response->withStatusCode(200);
        $this->assertSame($new, $response);
        $this->assertSame(200, $new->getStatusCode());
    }

    public function testWithStatusWithSameValidCodeReturnsSameInstance()
    {
        $response = new Response(200);
        $new = $response->withStatus(200);
        $this->assertSame($new, $response);
        $this->assertSame(200, $new->getStatusCode());
    }

    public function testStatusCodeThrowsExceptionForInvalidCode()
    {
        $this->expectException(InvalidArgumentException::class);
        new Response(99);
    }

    /**
     * @param int $code
     * @param string $reason
     *
     * @dataProvider validStatusCodeProvider
     */
    public function testDefaultResponseReason($code, $reason)
    {
        $response = new Response($code);
        $this->assertSame($reason, $response->getReasonPhrase());
    }

    public function testOverrideReasonPhrase()
    {
        $response = new Response(200, [], null, '1.1', 'hello');
        $this->assertSame('hello', $response->getReasonPhrase());
    }

    public function testSetReasonPhraseOverrideReasonPhrase()
    {
        $response = new Response();
        $response->setReasonPhrase('hello');
        $this->assertSame('hello', $response->getReasonPhrase());
    }

    public function testSetStatusOverrideReasonPhrase()
    {
        $response = new Response();
        $response->setStatus(200, 'test');
        $this->assertSame('test', $response->getReasonPhrase());
    }

    public function testWithReasonPhraseOverrideReasonPhrase()
    {
        $response = new Response();
        $response = $response->withReasonPhrase('test');
        $this->assertSame('test', $response->getReasonPhrase());
    }

    public function testWithReasonPhraseReturnsNewInstanceForNewReason()
    {
        $response = new Response();
        $new = $response->withReasonPhrase('test');
        $this->assertNotSame($response, $new);
        $this->assertSame('test', $new->getReasonPhrase());
    }

    public function testWithReasonPhraseReturnsSameInstanceForSameReason()
    {
        $response = new Response();
        $response->setReasonPhrase('test');
        $new = $response->withReasonPhrase('test');
        $this->assertSame($response, $new);
        $this->assertSame('test', $response->getReasonPhrase());
    }

    public function testWithStatusOverrideReasonPhrase()
    {
        $response = new Response();
        $response = $response->withStatus(200, 'hello');
        $this->assertSame('hello', $response->getReasonPhrase());
    }

    public function validStatusCodeProvider()
    {
        return [
            [100, 'Continue'],
            [101, 'Switching Protocols'],
            [102, 'Processing'],
            [200, 'OK'],
            [201, 'Created'],
            [202, 'Accepted'],
            [203, 'Non-Authoritative Information'],
            [204, 'No Content'],
            [205, 'Reset Content'],
            [206, 'Partial Content'],
            [207, 'Multi-Status'],
            [208, 'Already Reported'],
            [300, 'Multiple Choices'],
            [301, 'Moved Permanently'],
            [302, 'Found'],
            [303, 'See Other'],
            [304, 'Not Modified'],
            [305, 'Use Proxy'],
            [306, ''],
            [307, 'Temporary Redirect'],
            [400, 'Bad Request'],
            [401, 'Unauthorized'],
            [402, 'Payment Required'],
            [403, 'Forbidden'],
            [404, 'Not Found'],
            [405, 'Method Not Allowed'],
            [406, 'Not Acceptable'],
            [407, 'Proxy Authentication Required'],
            [408, 'Request Timeout'],
            [409, 'Conflict'],
            [410, 'Gone'],
            [411, 'Length Required'],
            [412, 'Precondition Failed'],
            [413, 'Payload Too Large'],
            [414, 'URI Too Long'],
            [415, 'Unsupported Media Type'],
            [416, 'Range Not Satisfiable'],
            [417, 'Expectation Failed'],
            [418, "I'm a teapot"],
            [422, 'Unprocessable Entity'],
            [423, 'Locked'],
            [424, 'Failed Dependency'],
            [425, 'Too Early'],
            [426, 'Upgrade Required'],
            [428, 'Precondition Required'],
            [429, 'Too Many Requests'],
            [431, 'Request Header Fields Too Large'],
            [451, 'Unavailable For Legal Reasons'],
            [500, 'Internal Server Error'],
            [501, 'Not Implemented'],
            [502, 'Bad Gateway'],
            [503, 'Service Unavailable'],
            [504, 'Gateway Timeout'],
            [505, 'HTTP Version Not Supported'],
            [506, 'Variant Also Negotiates'],
            [507, 'Insufficient Storage'],
            [508, 'Loop Detected'],
            [511, 'Network Authentication Required'],
        ];
    }
}
