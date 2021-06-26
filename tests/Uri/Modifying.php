<?php

namespace Kusabi\Http\Tests\Uri;

use Kusabi\Http\Exceptions\InvalidPortException;
use Kusabi\Http\Tests\TestCase;
use Kusabi\Http\Uri;

class Modifying extends TestCase
{
    /**
     * @covers \Kusabi\Http\Uri::setPort
     */
    public function testSetPortPortCanBeRemoved()
    {
        $original = new Uri('http://www.example.com:8080');
        $original->setPort(null);
        $this->assertNull($original->getPort());
    }

    /**
     * @covers \Kusabi\Http\Uri::setPort
     */
    public function testSetPortThrowsExceptionWhenAboveRange()
    {
        $this->expectException(InvalidPortException::class);
        $original = new Uri('http://www.example.com');
        $original->setPort(65536);
    }

    /**
     * @covers \Kusabi\Http\Uri::setPort
     */
    public function testSetPortThrowsExceptionWhenBelowRange()
    {
        $this->expectException(InvalidPortException::class);
        $original = new Uri('http://www.example.com');
        $original->setPort(-1);
    }

    /**
     * @covers \Kusabi\Http\Uri::setPort
     */
    public function testSetPortUpdatesPortOnThisInstance()
    {
        $original = new Uri('http://www.example.com:8080');
        $original->setPort(1234);
        $this->assertSame(1234, $original->getPort());
    }

    /**
     * @covers \Kusabi\Http\Uri::withFragment
     */
    public function testWithFragmentFragmentCanBeRemoved()
    {
        $original = new Uri('http://www.example.com#top');
        $changed = $original->withFragment('');
        $this->assertSame('', $changed->getFragment());
    }

    /**
     * @covers \Kusabi\Http\Uri::withFragment
     */
    public function testWithFragmentKeepsOriginalIntact()
    {
        $original = new Uri('http://www.example.com#top');
        $changed = $original->withFragment('bottom');
        $this->assertSame('top', $original->getFragment());
        $this->assertSame('bottom', $changed->getFragment());
    }

    /**
     * @covers \Kusabi\Http\Uri::withHost
     */
    public function testWithHostHostCanBeRemoved()
    {
        $original = new Uri('http://www.example.com');
        $changed = $original->withHost('');
        $this->assertSame('', $changed->getHost());
    }

    /**
     * @covers \Kusabi\Http\Uri::withHost
     */
    public function testWithHostKeepsOriginalIntactWhileReturningUpdatedInstance()
    {
        $original = new Uri('http://www.example.com');
        $changed = $original->withHost('test.co.uk');
        $this->assertSame('www.example.com', $original->getHost());
        $this->assertSame('test.co.uk', $changed->getHost());
    }

    /**
     * @covers \Kusabi\Http\Uri::withPath
     */
    public function testWithPathKeepsOriginalIntactWhileReturningUpdatedInstance()
    {
        $original = new Uri('http://www.example.com/example');
        $changed = $original->withPath('test');
        $this->assertSame('/example', $original->getPath());
        $this->assertSame('test', $changed->getPath());
    }

    /**
     * @covers \Kusabi\Http\Uri::withPort
     */
    public function testWithPortKeepsOriginalIntactWhileReturningUpdatedInstanc()
    {
        $original = new Uri('http://www.example.com:8080');
        $changed = $original->withPort(1234);
        $this->assertSame(8080, $original->getPort());
        $this->assertSame(1234, $changed->getPort());
    }

    /**
     * @covers \Kusabi\Http\Uri::withPort
     */
    public function testWithPortPortCanBeRemoved()
    {
        $original = new Uri('http://www.example.com:8080');
        $changed = $original->withPort(null);
        $this->assertNull($changed->getPort());
    }

    /**
     * @covers \Kusabi\Http\Uri::withPort
     */
    public function testWithPortThrowsExceptionWhenAboveRange()
    {
        $this->expectException(InvalidPortException::class);
        $original = new Uri('http://www.example.com');
        $original->withPort(65536);
    }

    /**
     * @covers \Kusabi\Http\Uri::withPort
     */
    public function testWithPortThrowsExceptionWhenBelowRange()
    {
        $this->expectException(InvalidPortException::class);
        $original = new Uri('http://www.example.com');
        $original->withPort(-1);
    }

    /**
     * @covers \Kusabi\Http\Uri::withQuery
     */
    public function testWithQueryKeepsOriginalIntactWhileReturningUpdatedInstance()
    {
        $original = new Uri('http://www.example.com?a=b');
        $changed = $original->withQuery('a[0]=b&a[1]=c');
        $this->assertSame('a=b', $original->getQuery());
        $this->assertSame('a[0]=b&a[1]=c', $changed->getQuery());
    }

    /**
     * @covers \Kusabi\Http\Uri::withQuery
     */
    public function testWithQueryQueryCanBeRemoved()
    {
        $original = new Uri('http://www.example.com?a=b');
        $changed = $original->withQuery('');
        $this->assertSame('', $changed->getQuery());
    }

    /**
     * @covers \Kusabi\Http\Uri::withScheme
     */
    public function testWithSchemeIsCaseInsensitive()
    {
        $original = new Uri('http://www.example.com');
        $this->assertSame('https', $original->withScheme('HTTPS')->getScheme());
    }

    /**
     * @covers \Kusabi\Http\Uri::withScheme
     */
    public function testWithSchemeKeepsOriginalIntactWhileReturningUpdatedInstance()
    {
        $original = new Uri('http://www.example.com');
        $changed = $original->withScheme('https');
        $this->assertSame('http', $original->getScheme());
        $this->assertSame('https', $changed->getScheme());
    }

    /**
     * @covers \Kusabi\Http\Uri::withUserInfo
     */
    public function testWithUserInfoKeepsOriginalIntactWhileReturningUpdatedInstance()
    {
        $original = new Uri('http://user:pass@www.example.com');
        $changed = $original->withUserInfo('userB', 'passB');
        $this->assertSame('user', $original->getUser());
        $this->assertSame('pass', $original->getPassword());
        $this->assertSame('user:pass', $original->getUserInfo());
        $this->assertSame('userB', $changed->getUser());
        $this->assertSame('passB', $changed->getPassword());
        $this->assertSame('userB:passB', $changed->getUserInfo());
    }

    /**
     * @covers \Kusabi\Http\Uri::withUserInfo
     */
    public function testWithUserPassCanBeRemoved()
    {
        $original = new Uri('http://user:pass@www.example.com');
        $changed = $original->withUserInfo('userB');

        $this->assertSame('userB', $changed->getUser());
        $this->assertSame('', $changed->getPassword());
        $this->assertSame('userB', $changed->getUserInfo());
    }

    /**
     * @covers \Kusabi\Http\Uri::withUserInfo
     */
    public function testWithUserUserCanBeRemoved()
    {
        $original = new Uri('http://user:pass@www.example.com');
        $changed = $original->withUserInfo('');
        $this->assertSame('', $changed->getUser());
        $this->assertSame('', $changed->getPassword());
        $this->assertSame('', $changed->getUserInfo());
    }
}
