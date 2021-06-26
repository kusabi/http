<?php

namespace Kusabi\Http;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Client implements ClientInterface
{
    /**
     * Sends a PSR-7 request and returns a PSR-7 response.
     *
     * @param RequestInterface $request
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface If an error happens while processing the request.
     *
     * @return ResponseInterface
     *
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
    }
}
