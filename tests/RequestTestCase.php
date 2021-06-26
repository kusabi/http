<?php

namespace Kusabi\Http\Tests;

class RequestTestCase extends TestCase
{
    public function providesInvalidRequestMethod(): array
    {
        return [
            ['option'],
            ['not-real'],
            [''],
            [' GET'],
            ['GET '],
        ];
    }

    public function providesUriToRequestTargets(): array
    {
        return [
            ['https://www.example.com', '/'],
            ['https://www.example.com/', '/'],
            ['https://www.example.com/some', '/some'],
            ['https://www.example.com/some/', '/some/'],
            ['https://www.example.com/some/path', '/some/path'],
            ['https://www.example.com/some/path?a=b&c=d', '/some/path?a=b&c=d'],
        ];
    }

    public function providesValidRequestMethods(): array
    {
        return [
            ['get'],
            ['GET'],
            ['head'],
            ['HEAD'],
            ['post'],
            ['POST'],
            ['put'],
            ['PUT'],
            ['delete'],
            ['DELETE'],
            ['connect'],
            ['CONNECT'],
            ['options'],
            ['OPTIONS'],
            ['trace'],
            ['TRACE'],
            ['patch'],
            ['PATCH']
        ];
    }
}
