<?php

namespace Kusabi\Http\Tests\Uri;

use Kusabi\Http\Tests\TestCase;
use Kusabi\Http\Uri;

class Converting extends TestCase
{
    /**
     * @covers \Kusabi\Http\Uri::__toString
     * @covers \Kusabi\Http\Uri::toString
     */
    public function testToStringWithFullUri()
    {
        $uri = new Uri('');
        $uri = $uri->withScheme('https')
            ->withUserInfo('user', 'pass')
            ->withHost('www.example.com')
            ->withPort(8080)
            ->withPath('/users/3')
            ->withQuery('a=b&c[1]=d')
            ->withFragment('top');
        $this->assertSame('https://user:pass@www.example.com:8080/users/3?a=b&c[1]=d#top', (string) $uri);
        $this->assertSame('https://user:pass@www.example.com:8080/users/3?a=b&c[1]=d#top', $uri->toString());
    }

    /**
     * @covers \Kusabi\Http\Uri::__toString
     * @covers \Kusabi\Http\Uri::toString
     */
    public function testToStringWithRootlessPathAndAuthority()
    {
        $uri = new Uri('');
        $uri = $uri->withScheme('https')
            ->withUserInfo('user', 'pass')
            ->withHost('www.example.com')
            ->withPort(8080)
            ->withPath('users/3')
            ->withQuery('a=b&c[1]=d')
            ->withFragment('top');
        $this->assertSame('https://user:pass@www.example.com:8080/users/3?a=b&c[1]=d#top', (string) $uri);
        $this->assertSame('https://user:pass@www.example.com:8080/users/3?a=b&c[1]=d#top', $uri->toString());
    }

    /**
     * @covers \Kusabi\Http\Uri::__toString
     * @covers \Kusabi\Http\Uri::toString
     */
    public function testToStringWithoutAuthorityAndMultipleStartingSlashes()
    {
        $uri = new Uri('');
        $uri = $uri->withPath('////users/3');
        $this->assertSame('/users/3', (string) $uri);
        $this->assertSame('/users/3', $uri->toString());
    }

    /**
     * @covers \Kusabi\Http\Uri::__toString
     * @covers \Kusabi\Http\Uri::toString
     */
    public function testVariousToStringOutputs()
    {
        $uri = new Uri('');
        $this->assertSame('', (string) $uri);
        $this->assertSame('', $uri->toString());

        $uri = $uri->withPath('help');
        $this->assertSame('help', (string) $uri);
        $this->assertSame('help', $uri->toString());

        $uri = $uri->withHost('example.com');
        $this->assertSame('//example.com/help', (string) $uri);
        $this->assertSame('//example.com/help', $uri->toString());

        $uri = $uri->withScheme('ftp');
        $this->assertSame('ftp://example.com/help', (string) $uri);
        $this->assertSame('ftp://example.com/help', $uri->toString());

        $uri = $uri->withPort(999);
        $this->assertSame('ftp://example.com:999/help', (string) $uri);
        $this->assertSame('ftp://example.com:999/help', $uri->toString());
    }
}
