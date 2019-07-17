<?php

namespace Kusabi\Http;

use InvalidArgumentException;
use Kusabi\Http\Exceptions\InvalidHeaderKeyException;
use Kusabi\Stream\Stream;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;
use RuntimeException;

abstract class AbstractMessage implements MessageInterface
{
    /**
     * The HTTP protocol used for this message
     *
     * @var string
     */
    protected $protocol = '1.1';

    /**
     * The headers associated with this message
     *
     * @var array
     */
    protected $headers = [];

    /**
     * Maintain a translation map of actual keys to overcome the case-insensitivity
     *
     * @var array
     *
     * @see https://www.php-fig.org/psr/psr-7/#case-insensitive-header-field-names
     */
    protected $headerKeys = [];

    /**
     * The HTTP message body as a stream
     *
     * @var StreamInterface
     */
    protected $body;

    /**
     * AbstractMessage constructor.
     *
     * @param array $headers
     * @param StreamInterface|resource|string|null $body
     * @param string $protocol
     */
    public function __construct(array $headers = [], $body = null, string $protocol = '1.1')
    {
        $this->setHeaders($headers);
        $this->setProtocolVersion($protocol);
        if ($body instanceof StreamInterface) {
            $this->setBody($body);
        } elseif (is_resource($body)) {
            $this->setBody(new Stream($body));
        } else {
            $stream = new Stream(fopen('php://temp', 'w+'));
            $stream->write((string) $body);
            $stream->rewind();
            $this->setBody($stream);
        }
    }

    /**
     * {@inheritDoc}
     *
     * @see MessageInterface::getProtocolVersion()
     */
    public function getProtocolVersion()
    {
        return $this->protocol;
    }

    /**
     * Set the HTTP protocol version.
     *
     * The version string MUST contain only the HTTP version number (e.g.,
     * "1.1", "1.0").
     *
     * This method DOES NOT retain the immutability of the message like methods from the PSR do.
     *
     * @param string $version HTTP protocol version
     *
     * @throws RuntimeException if protocol format is not valid
     *
     * @return void
     *
     */
    public function setProtocolVersion($version)
    {
        if (!preg_match('/^(\d+\.)+\d+$/', $version)) {
            throw new RuntimeException('Invalid protocol version format');
        }
        $this->protocol = $version;
    }

    /**
     * {@inheritDoc}
     *
     * @see MessageInterface::withProtocolVersion()
     */
    public function withProtocolVersion($version)
    {
        if ($this->protocol === $version) {
            return $this;
        }
        $new = clone $this;
        $new->setProtocolVersion($version);
        return $new;
    }

    /**
     * {@inheritDoc}
     *
     * @see MessageInterface::getHeaders()
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Set all of the headers at once.
     *
     * This will cause all current headers to be replaced.
     *
     * This method DOES NOT retain the immutability of the message like methods from the PSR do.
     *
     * @param array $headers
     */
    public function setHeaders(array $headers)
    {
        $this->headers = [];
        $this->headerKeys = [];
        foreach ($headers as $key => $value) {
            if (!$this->isValidHeaderKey($key)) {
                throw new InvalidHeaderKeyException($key);
            }
            $this->setHeader($key, $value);
        }
    }

    /**
     * Set all of the headers at once.
     *
     * This will create a new instance with the supplied headers.
     *
     * This method DOES retain the immutability of the message like methods from the PSR do.
     *
     * @param array $headers
     *
     * @return self
     */
    public function withHeaders(array $headers) : self
    {
        if ($headers === $this->headers) {
            return $this;
        }
        $new = clone $this;
        $new->setHeaders($headers);
        return $new;
    }

    /**
     * {@inheritDoc}
     *
     * @see MessageInterface::getHeader()
     */
    public function getHeader($name)
    {
        $key = $this->normaliseHeaderKey($name);

        if (!isset($this->headerKeys[$key])) {
            return [];
        }

        return (array) $this->headers[$this->headerKeys[$key]];
    }

    /**
     * Set a single header.
     *
     * This will overwrite an existing header if it matches the case (case insensitive).
     *
     * This method DOES NOT retain the immutability of the message like methods from the PSR do.
     *
     * @param string $name
     * @param string|string[] $value
     */
    public function setHeader($name, $value)
    {
        if (!$this->isValidHeaderKey($name)) {
            throw new InvalidHeaderKeyException($name);
        }

        // Get the normalised key
        $key = $this->normaliseHeaderKey($name);

        // If we have a key map for this, then we should remove the old and in with the new.
        // @see https://www.php-fig.org/psr/psr-7/#case-insensitive-header-field-names
        $this->removeHeader($name);

        $this->headerKeys[$key] = $name;
        $this->headers[$name] = $value;
    }

    /**
     * {@inheritDoc}
     *
     * @see MessageInterface::withHeader()
     */
    public function withHeader($name, $value)
    {
        if (!$this->isValidHeaderKey($name)) {
            throw new InvalidHeaderKeyException($name);
        }
        $new = clone $this;
        $new->setHeader($name, $value);
        return $new;
    }

    /**
     * Set the header.
     *
     * Existing values for the specified header will be maintained. The new
     * value(s) will be appended to the existing list. If the header did not
     * exist previously, it will be added.
     *
     * This method DOES NOT retain the immutability of the message like methods from the PSR do.
     *
     * @param string $name Case-insensitive header field name to add.
     * @param string|string[] $value Header value(s).
     *
     * @throws InvalidArgumentException for invalid header names or values.
     *
     * @return void
     */
    public function addHeader($name, $value)
    {
        if (!$this->isValidHeaderKey($name)) {
            throw new InvalidHeaderKeyException($name);
        }
        if ($this->hasHeader($name)) {
            $this->setHeader($this->headerKeys[$this->normaliseHeaderKey($name)], array_merge(
                (array) $this->getHeader($name),
                (array) $value
            ));
        } else {
            $this->setHeader($name, $value);
        }
    }

    /**
     * {@inheritDoc}
     *
     * @see MessageInterface::withAddedHeader()
     */
    public function withAddedHeader($name, $value)
    {
        if (!$this->isValidHeaderKey($name)) {
            throw new InvalidHeaderKeyException($name);
        }
        $new = clone $this;
        $new->addHeader($name, $value);
        return $new;
    }

    /**
     * {@inheritDoc}
     *
     * @see MessageInterface::getHeaderLine()
     */
    public function getHeaderLine($name)
    {
        if (!$this->hasHeader($name)) {
            return '';
        }
        return implode(',', (array) $this->getHeader($name));
    }

    /**
     * Remove the specified header.
     *
     * Header resolution MUST be done without case-sensitivity.
     *
     * This method DOES NOT retain the immutability of the message like methods from the PSR do.
     *
     * @param string $name Case-insensitive header field name to remove
     *
     * @return void
     */
    public function removeHeader($name)
    {
        if (!$this->isValidHeaderKey($name)) {
            throw new InvalidHeaderKeyException($name);
        }
        if (!$this->hasHeader($name)) {
            return;
        }
        $key = $this->normaliseHeaderKey($name);
        if (isset($this->headerKeys[$key])) {
            unset($this->headers[$this->headerKeys[$key]]);
            unset($this->headerKeys[$key]);
        }
    }

    /**
     * {@inheritDoc}
     *
     * @see MessageInterface::withoutHeader()
     */
    public function withoutHeader($name)
    {
        if (!$this->isValidHeaderKey($name)) {
            throw new InvalidHeaderKeyException($name);
        }
        $new = clone $this;
        $new->removeHeader($name);
        return $new;
    }

    /**
     * {@inheritDoc}
     *
     * @see MessageInterface::hasHeader()
     */
    public function hasHeader($name)
    {
        return isset($this->headerKeys[$this->normaliseHeaderKey($name)]);
    }

    /**
     * {@inheritDoc}
     *
     * @see MessageInterface::getBody()
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set the message body
     *
     * This method DOES NOT retain the immutability of the message like methods from the PSR do.
     *
     * @param StreamInterface $body
     */
    public function setBody(StreamInterface $body)
    {
        $this->body = $body;
    }

    /**
     * {@inheritDoc}
     *
     * @see MessageInterface::withBody()
     */
    public function withBody(StreamInterface $body)
    {
        if ($body === $this->body) {
            return $this;
        }
        $new = clone $this;
        $new->setBody($body);
        return $new;
    }

    /**
     * Normalise a header key into a case-insensitive form
     *
     * @param string $key
     *
     * @return string
     */
    protected function normaliseHeaderKey($key): string
    {
        return strtolower($key);
    }

    /**
     * Returns true if key is valid for a header name
     *
     * Returns false otherwise
     *
     * @param string $key
     *
     * @return bool
     */
    protected function isValidHeaderKey($key): bool
    {
        return is_string($key) && $key != '';
    }
}
