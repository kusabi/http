<?php

namespace Kusabi\Http;

use Kusabi\Http\Exceptions\InvalidUriException;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;

/**
 * A Uri Factory class implementation that conforms to PSR-17
 *
 * @author Christian Harvey <kusabi.software@gmail.com>
 *
 * @see UriFactoryInterface
 */
class UriFactory implements UriFactoryInterface
{
    /**
     * {@inheritdoc}
     *
     * @throws InvalidUriException if the URI cannot be parsed.
     *
     * @return Uri
     *
     * @see UriFactoryInterface::createUri()
     */
    public function createUri(string $uri = ''): UriInterface
    {
        return new Uri($uri);
    }
}
