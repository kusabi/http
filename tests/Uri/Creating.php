<?php

namespace Kusabi\Http\Tests\Uri;

use Kusabi\Http\Exceptions\InvalidUriException;
use Kusabi\Http\Tests\TestCase;
use Kusabi\Http\Uri;

class Creating extends TestCase
{
    /**
     * @covers \Kusabi\Http\Uri::__construct
     */
    public function testCreateFullExample()
    {
        $uri = new Uri('https://user:pass@www.example.com:8080/users/3?a=b&c[1]=d#top');
        $this->assertSame('https', $uri->getScheme());
        $this->assertSame('user', $uri->getUser());
        $this->assertSame('pass', $uri->getPassword());
        $this->assertSame('user:pass', $uri->getUserInfo());
        $this->assertSame('www.example.com', $uri->getHost());
        $this->assertSame(8080, $uri->getPort());
        $this->assertSame('/users/3', $uri->getPath());
        $this->assertSame('a=b&c[1]=d', $uri->getQuery());
        $this->assertSame('top', $uri->getFragment());
    }

    /**
     * @covers \Kusabi\Http\Uri::__construct
     */
    public function testThrowsExceptionIfUriIsMalformed()
    {
        $this->expectException(InvalidUriException::class);
        new Uri('host:65536');
    }
}
