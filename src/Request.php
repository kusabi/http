<?php

namespace Kusabi\Http;

use InvalidArgumentException;
use Kusabi\Http\Exceptions\InvalidHttpMethodException;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class Request extends Message implements RequestInterface
{
    /**
     * The GET method requests a representation of the specified resource.
     * Requests using GET should only retrieve data.
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Methods/GET
     *
     * @var string
     */
    public const METHOD_GET = 'GET';

    /**
     * The HEAD method asks for a response identical to that of a GET request, but without the response body.
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Methods/HEAD
     *
     * @var string
     */
    public const METHOD_HEAD = 'HEAD';

    /**
     * The POST method is used to submit an entity to the specified resource, often causing a change in state or side effects on the server.
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Methods/POST
     *
     * @var string
     */
    public const METHOD_POST = 'POST';

    /**
     * The PUT method replaces all current representations of the target resource with the request payload.
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Methods/PUT
     *
     * @var string
     */
    public const METHOD_PUT = 'PUT';

    /**
     * The DELETE method deletes the specified resource.
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Methods/DELETE
     *
     * @var string
     */
    public const METHOD_DELETE = 'DELETE';

    /**
     * The CONNECT method establishes a tunnel to the server identified by the target resource.
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Methods/CONNECT
     *
     * @var string
     */
    public const METHOD_CONNECT = 'CONNECT';

    /**
     * The OPTIONS method is used to describe the communication options for the target resource.
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Methods/OPTIONS
     *
     * @var string
     */
    public const METHOD_OPTIONS = 'OPTIONS';

    /**
     * The TRACE method performs a message loop-back test along the path to the target resource.
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Methods/TRACE
     *
     * @var string
     */
    public const METHOD_TRACE = 'TRACE';

    /**
     * The PATCH method is used to apply partial modifications to a resource.
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Methods/PATCH
     *
     * @var string
     */
    public const METHOD_PATCH = 'PATCH';

    /**
     * A list of valid HTTP methods
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Methods
     *
     * @var array
     */
    public const METHODS = [
        self::METHOD_GET,
        self::METHOD_HEAD,
        self::METHOD_POST,
        self::METHOD_PUT,
        self::METHOD_DELETE,
        self::METHOD_CONNECT,
        self::METHOD_OPTIONS,
        self::METHOD_TRACE,
        self::METHOD_PATCH
    ];

    /**
     * The request method verb
     *
     * @var string
     */
    protected $method = '';

    /**
     * The request target
     *
     * @var string
     */
    protected $requestTarget = '';

    /**
     * The request uri
     *
     * @var Uri|UriInterface
     */
    protected $uri;

    /**
     * Request constructor.
     *
     * @param string $method
     * @param UriInterface|string $uri
     * @param array $headers
     * @param StreamInterface|resource|string|null $body
     * @param string $version
     */
    public function __construct(string $method, $uri, array $headers = [], $body = null, string $version = '1.1')
    {
        parent::__construct($headers, $body, $version);

        // Set the HTTP method
        $this->setMethod($method);

        // Set URI
        $uriFactory = new UriFactory();
        $this->setUri($uriFactory->createUri($uri));
    }

    /**
     * {@inheritDoc}
     *
     * @see RequestInterface::getRequestTarget()
     */
    public function getRequestTarget()
    {
        if ($this->requestTarget !== '') {
            return $this->requestTarget;
        }

        $target = $this->uri->getPath();
        if ($target == '') {
            $target = '/';
        }

        if ($this->uri->getQuery() !== '') {
            $target .= '?'.$this->uri->getQuery();
        }

        return $target;
    }

    /**
     * Set the request-target.
     *
     * If the request needs a non-origin-form request-target — e.g., for
     * specifying an absolute-form, authority-form, or asterisk-form —
     * this method may be used to create an instance with the specified
     * request-target, verbatim.
     *
     * This method DOES NOT retain the immutability of the message like methods from the PSR do.
     *
     * @link http://tools.ietf.org/html/rfc7230#section-5.3 (for the various request-target forms allowed in request messages)
     *
     * @param mixed $requestTarget
     */
    public function setRequestTarget($requestTarget)
    {
        $this->requestTarget = (string) $requestTarget;
    }

    /**
     * {@inheritDoc}
     *
     * @see RequestInterface::withRequestTarget()
     */
    public function withRequestTarget($requestTarget)
    {
        if ($requestTarget === $this->requestTarget) {
            return $this;
        }
        $new = clone $this;
        $new->setRequestTarget($requestTarget);
        return $new;
    }

    /**
     * {@inheritDoc}
     *
     * @see RequestInterface::getMethod()
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Set the HTTP method.
     *
     * While HTTP method names are typically all uppercase characters, HTTP
     * method names are case-sensitive and thus implementations SHOULD NOT
     * modify the given string.
     *
     * This method DOES NOT retain the immutability of the message like methods from the PSR do.
     *
     * @param string $method Case-sensitive method.
     *
     * @throws InvalidArgumentException for invalid HTTP methods.
     */
    public function setMethod($method)
    {
        $upperCaseMethod = strtoupper($method);
        if (!in_array($upperCaseMethod, self::METHODS)) {
            throw new InvalidHttpMethodException($method);
        }
        $this->method = $method;
    }

    /**
     * {@inheritDoc}
     *
     * @see RequestInterface::withMethod()
     */
    public function withMethod($method)
    {
        if ($this->method == $method) {
            return $this;
        }
        $new = clone $this;
        $new->setMethod($method);
        return $new;
    }

    /**
     * {@inheritDoc}
     *
     * @see RequestInterface::getUri()
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Returns an instance with the provided URI.
     *
     * This method MUST update the Host header of the returned request by
     * default if the URI contains a host component. If the URI does not
     * contain a host component, any pre-existing Host header MUST be carried
     * over to the returned request.
     *
     * You can opt-in to preserving the original state of the Host header by
     * setting `$preserveHost` to `true`. When `$preserveHost` is set to
     * `true`, this method interacts with the Host header in the following ways:
     *
     * - If the Host header is missing or empty, and the new URI contains
     *   a host component, this method MUST update the Host header in the returned
     *   request.
     * - If the Host header is missing or empty, and the new URI does not contain a
     *   host component, this method MUST NOT update the Host header in the returned
     *   request.
     * - If a Host header is present and non-empty, this method MUST NOT update
     *   the Host header in the returned request.
     *
     * This method DOES NOT retain the immutability of the message like methods from the PSR do.
     *
     * @link http://tools.ietf.org/html/rfc3986#section-4.3
     *
     * @param UriInterface $uri New request URI to use.
     * @param bool $preserveHost Preserve the original state of the Host header.
     */
    public function setUri(UriInterface $uri, $preserveHost = false)
    {
        $this->uri = $uri;

        $uriHost = $uri->getHost();
        if (!$preserveHost && !empty($uriHost)) {
            $this->setHeader('Host', $uriHost);
        } elseif (empty($this->getHeader('Host')) && !empty($uriHost)) {
            $this->setHeader('Host', $uriHost);
        }
    }

    /**
     * {@inheritDoc}
     *
     * @see RequestInterface::withUri()
     */
    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        $new = clone $this;
        $new->setUri($uri, $preserveHost);
        return $new;
    }
}
