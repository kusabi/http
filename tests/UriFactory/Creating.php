<?php

namespace Kusabi\Http\Tests\UriFactory;

use Kusabi\Http\Exceptions\InvalidUriException;
use Kusabi\Http\Tests\TestCase;
use Kusabi\Http\Uri;
use Kusabi\Http\UriFactory;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;

class Creating extends TestCase
{
    public function testCanBeInstantiated()
    {
        $this->assertInstanceOf(UriFactoryInterface::class, new UriFactory());
    }

    /**
     * @covers \Kusabi\Http\UriFactory::createUri
     */
    public function testCreatesInstanceOfUri()
    {
        $uriFactory = new UriFactory();
        $uri = $uriFactory->createUri('/users/1');
        $this->assertInstanceOf(UriInterface::class, $uri);
        $this->assertInstanceOf(Uri::class, $uri);
    }

    /**
     * @covers \Kusabi\Http\UriFactory::createUri
     */
    public function testThrowsExceptionIfUriIsMalformed()
    {
        $this->expectException(InvalidUriException::class);
        $uriFactory = new UriFactory();
        $uriFactory->createUri('host:65536');
    }
}
