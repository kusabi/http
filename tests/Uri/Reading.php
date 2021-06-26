<?php

namespace Kusabi\Http\Tests\Uri;

use Kusabi\Http\Tests\TestCase;
use Kusabi\Http\Uri;

class Reading extends TestCase
{
    /**
     * @see https://tools.ietf.org/html/rfc3986#section-3.
     *
     * @covers \Kusabi\Http\Uri::getAuthority
     */
    public function testGetAuthorityMustReturnEmptyIfNonePresent()
    {
        $uri = new Uri('users');
        $this->assertSame('', $uri->getAuthority());
    }

    /**
     * @see https://tools.ietf.org/html/rfc3986#section-3.
     *
     * @covers \Kusabi\Http\Uri::getAuthority
     */
    public function testGetAuthorityShouldNotShowPortIfItIsStandardForScheme()
    {
        $uri = new Uri('http://user@www.example.com:80');
        $this->assertSame('user@www.example.com', $uri->getAuthority());

        $uri = new Uri('https://user@www.example.com:443');
        $this->assertSame('user@www.example.com', $uri->getAuthority());
    }

    /**
     * @see https://tools.ietf.org/html/rfc3986#section-3.
     *
     * @covers \Kusabi\Http\Uri::getAuthority
     */
    public function testGetAuthorityShouldReturnHostIfSet()
    {
        $uri = new Uri('http://www.example.com');
        $this->assertSame('www.example.com', $uri->getAuthority());
    }

    /**
     * @see https://tools.ietf.org/html/rfc3986#section-3.
     *
     * @covers \Kusabi\Http\Uri::getAuthority
     */
    public function testGetAuthorityShouldReturnPortIfSet()
    {
        $uri = new Uri('http://user@www.example.com:1234');
        $this->assertSame('user@www.example.com:1234', $uri->getAuthority());

        $uri = new Uri('http://user:pass@www.example.com:1234');
        $this->assertSame('user:pass@www.example.com:1234', $uri->getAuthority());
    }

    /**
     * @see https://tools.ietf.org/html/rfc3986#section-3.
     *
     * @covers \Kusabi\Http\Uri::getAuthority
     */
    public function testGetAuthorityShouldReturnUserInfoIfSet()
    {
        $uri = new Uri('http://user@www.example.com');
        $this->assertSame('user@www.example.com', $uri->getAuthority());

        $uri = new Uri('http://user:pass@www.example.com');
        $this->assertSame('user:pass@www.example.com', $uri->getAuthority());
    }

    /**
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.4
     *
     * @covers \Kusabi\Http\Uri::getFragment
     */
    public function testGetFragmentMustBeEmptyWhenNotSet()
    {
        $uri = new Uri('');
        $this->assertSame('', $uri->getFragment());
    }

    /**
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.4
     *
     * @covers \Kusabi\Http\Uri::getFragment
     */
    public function testGetFragmentReturned()
    {
        $uri = new Uri('index.php#top');
        $this->assertSame('top', $uri->getFragment());
    }

    /**
     * @see http://tools.ietf.org/html/rfc3986#section-3.2.2
     *
     * @covers \Kusabi\Http\Uri::getHost
     */
    public function testGetHostMustReturnLowerCase()
    {
        $uri = new Uri('http://WwW.EXAMPLE.com');
        $this->assertSame('www.example.com', $uri->getHost());
    }

    /**
     * @covers \Kusabi\Http\Uri::getPassword
     */
    public function testGetPasswordMustReturnEmptyStringIfNonePresent()
    {
        $uri = new Uri('http://www.example.com/users');
        $this->assertSame('', $uri->getPassword());
    }

    /**
     * @covers \Kusabi\Http\Uri::getPassword
     */
    public function testGetPasswordShouldReturnEmptyIfJustUserIsSet()
    {
        $uri = new Uri('http://user@www.example.com/users');
        $this->assertSame('', $uri->getPassword());
    }

    /**
     * @covers \Kusabi\Http\Uri::getPassword
     */
    public function testGetPasswordShouldReturnIfSetWithoutUser()
    {
        $uri = new Uri('http://:pass@www.example.com/users');
        $this->assertSame('pass', $uri->getPassword());
    }

    /**
     * @covers \Kusabi\Http\Uri::getPassword
     */
    public function testGetPasswordShouldReturnPasswordWhenSet()
    {
        $uri = new Uri('http://user:pass@www.example.com/users');
        $this->assertSame('pass', $uri->getPassword());
    }

    /**
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.3
     *
     * @covers \Kusabi\Http\Uri::getPath
     */
    public function testGetPathCanBeEmpty()
    {
        $uri = new Uri('http://user@www.example.com');
        $this->assertSame('', $uri->getPath());

        $uri = new Uri('');
        $this->assertSame('', $uri->getPath());
    }

    /**
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.3
     *
     * @covers \Kusabi\Http\Uri::getPath
     */
    public function testGetPathCanBeRelative()
    {
        $uri = new Uri('user/index');
        $this->assertSame('user/index', $uri->getPath());

        $uri = new Uri('user/index');
        $this->assertSame('user/index', $uri->getPath());
    }

    /**
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.3
     *
     * @covers \Kusabi\Http\Uri::getPath
     */
    public function testGetPathCanBeRoot()
    {
        $uri = new Uri('http://user@www.example.com/user/index');
        $this->assertSame('/user/index', $uri->getPath());

        $uri = new Uri('/user/index');
        $this->assertSame('/user/index', $uri->getPath());
    }

    /**
     * @covers \Kusabi\Http\Uri::getPort
     */
    public function testGetPortReturnsIntegerForNonStandardPorts()
    {
        $uri = new Uri('http://user@www.example.com:8080');
        $this->assertSame(8080, $uri->getPort());
    }

    /**
     * @covers \Kusabi\Http\Uri::getPort
     */
    public function testGetPortReturnsNullWhenNotSet()
    {
        $uri = new Uri('http://user@www.example.com');
        $this->assertNull($uri->getPort());
    }

    /**
     * @covers \Kusabi\Http\Uri::getPort
     */
    public function testGetPortShouldReturnNullForStandardPorts()
    {
        $uri = new Uri('http://user@www.example.com:80');
        $this->assertNull($uri->getPort());

        $uri = new Uri('https://user@www.example.com:443');
        $this->assertNull($uri->getPort());
    }

    /**
     * @covers \Kusabi\Http\Uri::getPortValue
     */
    public function testGetPortValueReturnsIntegerForNonStandardPorts()
    {
        $uri = new Uri('http://user@www.example.com:8080');
        $this->assertSame(8080, $uri->getPortValue());
    }

    /**
     * @covers \Kusabi\Http\Uri::getPortValue
     */
    public function testGetPortValueReturnsNullWhenNotSet()
    {
        $uri = new Uri('http://user@www.example.com');
        $this->assertNull($uri->getPortValue());
    }

    /**
     * @covers \Kusabi\Http\Uri::getPortValue
     */
    public function testGetPortValueShouldReturnValueForStandardPorts()
    {
        $uri = new Uri('http://user@www.example.com:80');
        $this->assertSame(80, $uri->getPortValue());

        $uri = new Uri('https://user@www.example.com:443');
        $this->assertSame(443, $uri->getPortValue());
    }

    /**
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.4
     *
     * @covers \Kusabi\Http\Uri::getQuery
     */
    public function testGetQueryMustBeEmptyWhenNotSet()
    {
        $uri = new Uri('');
        $this->assertSame('', $uri->getQuery());
    }

    /**
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.4
     *
     * @covers \Kusabi\Http\Uri::getQuery
     */
    public function testGetQueryReturned()
    {
        $uri = new Uri('index.php?a=b&c[1]=d');
        $this->assertSame('a=b&c[1]=d', $uri->getQuery());
    }

    /**
     * @see https://tools.ietf.org/html/rfc3986#section-3.1
     *
     * @covers \Kusabi\Http\Uri::getScheme
     */
    public function testGetSchemeMustReturnLowerCase()
    {
        $uri = new Uri('HTTPS://www.example.com');
        $this->assertSame('https', $uri->getScheme());
    }

    /**
     * @covers \Kusabi\Http\Uri::getStandardPortForScheme
     */
    public function testGetStandardPortForScheme()
    {
        $this->assertSame(21, Uri::getStandardPortForScheme('ftp'));
        $this->assertSame(22, Uri::getStandardPortForScheme('ssh'));
        $this->assertSame(23, Uri::getStandardPortForScheme('telnet'));
        $this->assertSame(25, Uri::getStandardPortForScheme('smtp'));
        $this->assertSame(53, Uri::getStandardPortForScheme('dns'));
        $this->assertSame(69, Uri::getStandardPortForScheme('tftp'));
        $this->assertSame(80, Uri::getStandardPortForScheme('http'));
        $this->assertSame(115, Uri::getStandardPortForScheme('sftp'));
        $this->assertSame(443, Uri::getStandardPortForScheme('https'));
    }

    /**
     * @covers \Kusabi\Http\Uri::getUserInfo
     */
    public function testGetUserInfoMustReturnEmptyStringIfNonePresent()
    {
        $uri = new Uri('http://www.example.com/users');
        $this->assertSame('', $uri->getUserInfo());
    }

    /**
     * @covers \Kusabi\Http\Uri::getUserInfo
     */
    public function testGetUserInfoShouldReturnEmptyIfNoUserIsSet()
    {
        $uri = new Uri('http://:pass@www.example.com/users');
        $this->assertSame('', $uri->getUserInfo());
    }

    /**
     * @covers \Kusabi\Http\Uri::getUserInfo
     */
    public function testGetUserInfoShouldReturnIfJustUserIsSet()
    {
        $uri = new Uri('http://user@www.example.com/users');
        $this->assertSame('user', $uri->getUserInfo());
    }

    /**
     * @covers \Kusabi\Http\Uri::getUserInfo
     */
    public function testGetUserInfoShouldReturnPasswordWhenSet()
    {
        $uri = new Uri('http://user:pass@www.example.com/users');
        $this->assertSame('user:pass', $uri->getUserInfo());
    }

    /**
     * @covers \Kusabi\Http\Uri::getUser
     */
    public function testGetUserMustReturnEmptyStringIfNonePresent()
    {
        $uri = new Uri('http://www.example.com/users');
        $this->assertSame('', $uri->getUser());
    }

    /**
     * @covers \Kusabi\Http\Uri::getUser
     */
    public function testGetUserShouldReturnEmptyIfJustPasswordIsSet()
    {
        $uri = new Uri('http://:pass@www.example.com/users');
        $this->assertSame('', $uri->getUser());
    }

    /**
     * @covers \Kusabi\Http\Uri::getUser
     */
    public function testGetUserShouldReturnIfJustUserIsSet()
    {
        $uri = new Uri('http://user@www.example.com/users');
        $this->assertSame('user', $uri->getUser());
    }

    /**
     * @covers \Kusabi\Http\Uri::getUser
     */
    public function testGetUserShouldReturnUserWhenUserAndPassSet()
    {
        $uri = new Uri('http://user:pass@www.example.com/users');
        $this->assertSame('user', $uri->getUser());
    }

    /**
     * @covers \Kusabi\Http\Uri::validPort
     */
    public function testValidPort()
    {
        $this->assertFalse(Uri::validPort(-1));
        $this->assertTrue(Uri::validPort(0));
        $this->assertTrue(Uri::validPort(65535));
        $this->assertFalse(Uri::validPort(65536));
    }
}
