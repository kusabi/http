<?php

namespace Kusabi\Http;

use Kusabi\Http\Exceptions\InvalidPortException;
use Kusabi\Http\Exceptions\InvalidUriException;
use Psr\Http\Message\UriInterface;

/**
 * A Uri wrapper class implementation that conforms to PSR-7
 *
 * @see UriInterface
 *
 * @author Christian Harvey <kusabi.software@gmail.com>
 *
 */
class Uri implements UriInterface
{
    /**
     * The largest allowed port range
     *
     * @var int
     */
    public const PORT_MAX = 65535;

    /**
     * The smallest allowed port range
     *
     * @var int
     */
    public const PORT_MIN = 0;

    /**
     * A list of common ports for schemes
     *
     * @var array
     */
    public const SCHEME_PORTS = [
        'ftp' => 21,
        'ssh' => 22,
        'telnet' => 23,
        'smtp' => 25,
        'dns' => 53,
        'tftp' => 69,
        'http' => 80,
        'sftp' => 115,
        'https' => 443,
    ];

    /**
     * The uri fragment
     *
     * @var string
     */
    protected $fragment;

    /**
     * The uri host
     *
     * @var string
     */
    protected $host;

    /**
     * The uri password
     *
     * @var string
     */
    protected $password;

    /**
     * The uri path
     *
     * @var string
     */
    protected $path;

    /**
     * The uri port
     *
     * @var string
     */
    protected $port;

    /**
     * The uri query string
     *
     * @var string
     */
    protected $query;

    /**
     * The uri scheme
     *
     * @var string
     */
    protected $scheme;

    /**
     * The uri user
     *
     * @var string
     */
    protected $user;

    /**
     * Uri constructor.
     *
     * @param string $uri
     *
     * @throws InvalidUriException if the URI is malformed
     */
    public function __construct(string $uri = '')
    {
        // Parse the URI
        $parsed = parse_url($uri);

        // Was the URI malformed?
        if ($parsed === false) {
            throw new InvalidUriException($uri);
        }

        // Fetch the values
        $this->scheme = $parsed['scheme'] ?? '';
        $this->host = $parsed['host'] ?? '';
        $this->port = $parsed['port'] ?? '';
        $this->user = $parsed['user'] ?? '';
        $this->password = $parsed['pass'] ?? '';
        $this->path = $parsed['path'] ?? '';
        $this->query = $parsed['query'] ?? '';
        $this->fragment = $parsed['fragment'] ?? '';
    }

    /**
     * Get standard port number for the supplied scheme
     * Returns null if scheme is not known
     *
     * @param string $scheme
     *
     * @return int|null
     */
    public static function getStandardPortForScheme(string $scheme)
    {
        return self::SCHEME_PORTS[strtolower($scheme)] ?? null;
    }

    /**
     * Check if the port is in a valid range
     *
     * @param int|mixed $port
     *
     * @return bool
     */
    public static function validPort($port): bool
    {
        return $port >= self::PORT_MIN && $port <= self::PORT_MAX;
    }

    /**
     * {@inheritdoc}
     *
     * @see UriInterface::__toString()
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * {@inheritdoc}
     *
     * @see UriInterface::getAuthority()
     */
    public function getAuthority(): string
    {
        $host = $this->getHost();
        $userInfo = $this->getUserInfo();
        $port = $this->getPort();

        // Return blank if there is no host info
        if (!$host) {
            return '';
        }

        // Join the user-info, host and port info together, but leave user-info and port off if it was not set
        return implode('', array_filter([
            $userInfo ? $userInfo.'@' : null,
            $host,
            $port ? ':'.$port : null,
        ]));
    }

    /**
     * {@inheritdoc}
     *
     * @see UriInterface::getFragment()
     */
    public function getFragment()
    {
        return $this->fragment;
    }

    /**
     * {@inheritdoc}
     *
     * @see UriInterface::getHost()
     */
    public function getHost(): string
    {
        return strtolower($this->host);
    }

    /**
     * Get the password from the URI
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * {@inheritdoc}
     *
     * @see UriInterface::getPath()
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * {@inheritdoc}
     *
     * @see UriInterface::getPort()
     */
    public function getPort()
    {
        $current = $this->getPortValue();
        $default = static::getStandardPortForScheme($this->getScheme());

        if ($current && $current !== $default) {
            return $current;
        }

        return null;
    }

    /**
     * Set the port
     *
     * @param int|mixed $port
     *
     * @return static
     */
    public function setPort($port): self
    {
        if (!static::validPort($port)) {
            throw new InvalidPortException((string) $port);
        }
        $this->port = $port;
        return $this;
    }

    /**
     * Get the value fo the supplied port
     *
     * @return int|null
     */
    public function getPortValue()
    {
        return $this->port ? (int) $this->port : null;
    }

    /**
     * {@inheritdoc}
     *
     * @see UriInterface::getQuery()
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * {@inheritdoc}
     *
     * @see UriInterface::getScheme()
     */
    public function getScheme(): string
    {
        return strtolower($this->scheme);
    }

    /**
     * Get the username from the URI
     *
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * {@inheritdoc}
     *
     * @see UriInterface::getUserInfo()
     */
    public function getUserInfo(): string
    {
        // Return blank if there is no user info
        if (!$this->getUser()) {
            return '';
        }

        // Join the user and password info together, but leave password off if it was not set
        return implode(':', array_filter([
            $this->getUser(),
            $this->getPassword()
        ]));
    }

    /**
     * Convert the Uri to a string
     *
     * @return string
     */
    public function toString(): string
    {
        // Get some variables
        $path = $this->getPath();
        $authority = $this->getAuthority();
        $scheme = $this->getScheme();
        $query = $this->getQuery();
        $fragment = $this->getFragment();

        if ($path) {
            // Path Rule #1: If the path is rootless and an authority is present, the path MUST be prefixed by “/“
            if ($authority && substr($path, 0, 1) != '/') {
                $path = '/'.$path;
            }

            // Path Rule #2: If the path is starting with more than one “/” and no authority is present, the starting slashes MUST be reduced to one
            if (!$authority && substr($path, 0, 2) == '//') {
                $path = '/'.ltrim($path, '/');
            }
        }

        return implode('', array_filter([
            $scheme ? $scheme.':' : null,
            $authority ? '//'.$authority : null,
            $path,
            $query ? '?'.$query : null,
            $fragment ? '#'.$fragment : null,
        ]));
    }

    /**
     * {@inheritdoc}
     *
     * @see UriInterface::withFragment()
     */
    public function withFragment($fragment)
    {
        $result = clone $this;
        $result->fragment = $fragment;
        return $result;
    }

    /**
     * {@inheritdoc}
     *
     * @see UriInterface::withHost()
     */
    public function withHost($host)
    {
        $result = clone $this;
        $result->host = $host;
        return $result;
    }

    /**
     * {@inheritdoc}
     *
     * @see UriInterface::withPath()
     */
    public function withPath($path)
    {
        $result = clone $this;
        $result->path = $path;
        return $result;
    }

    /**
     * {@inheritdoc}
     *
     * @see UriInterface::withPort()
     */
    public function withPort($port)
    {
        $result = clone $this;
        return $result->setPort($port);
    }

    /**
     * {@inheritdoc}
     *
     * @see UriInterface::withQuery()
     */
    public function withQuery($query)
    {
        $result = clone $this;
        $result->query = $query;
        return $result;
    }

    /**
     * {@inheritdoc}
     *
     * @see UriInterface::withScheme()
     */
    public function withScheme($scheme)
    {
        $result = clone $this;
        $result->scheme = $scheme;
        return $result;
    }

    /**
     * {@inheritdoc}
     *
     * @see UriInterface::withUserInfo()
     */
    public function withUserInfo($user, $password = null)
    {
        $result = clone $this;
        $result->user = $user;
        $result->password = (string) $password;
        return $result;
    }
}
