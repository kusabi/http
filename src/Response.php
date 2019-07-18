<?php

namespace Kusabi\Http;

use InvalidArgumentException;
use Kusabi\Http\Exceptions\InvalidStatusCodeException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class Response extends AbstractMessage implements ResponseInterface
{
    const STATUS_CONTINUE = 100;

    const STATUS_SWITCHING_PROTOCOLS = 101;

    const STATUS_PROCESSING = 102;

    const STATUS_EARLY_HINTS = 103;

    const STATUS_OK = 200;

    const STATUS_CREATED = 201;

    const STATUS_ACCEPTED = 202;

    const STATUS_NON_AUTHORITATIVE_INFORMATION = 203;

    const STATUS_NO_CONTENT = 204;

    const STATUS_RESET_CONTENT = 205;

    const STATUS_PARTIAL_CONTENT = 206;

    const STATUS_MULTI_STATUS = 207;

    const STATUS_ALREADY_REPORTED = 208;

    const STATUS_IM_USED = 226;

    const STATUS_MULTIPLE_CHOICES = 300;

    const STATUS_MOVED_PERMANENTLY = 301;

    const STATUS_FOUND = 302;

    const STATUS_SEE_OTHER = 303;

    const STATUS_NOT_MODIFIED = 304;

    const STATUS_USE_PROXY = 305;

    const STATUS_RESERVED = 306;

    const STATUS_TEMPORARY_REDIRECT = 307;

    const STATUS_PERMANENTLY_REDIRECT = 308;

    const STATUS_BAD_REQUEST = 400;

    const STATUS_UNAUTHORIZED = 401;

    const STATUS_PAYMENT_REQUIRED = 402;

    const STATUS_FORBIDDEN = 403;

    const STATUS_NOT_FOUND = 404;

    const STATUS_METHOD_NOT_ALLOWED = 405;

    const STATUS_NOT_ACCEPTABLE = 406;

    const STATUS_PROXY_AUTHENTICATION_REQUIRED = 407;

    const STATUS_REQUEST_TIMEOUT = 408;

    const STATUS_CONFLICT = 409;

    const STATUS_GONE = 410;

    const STATUS_LENGTH_REQUIRED = 411;

    const STATUS_PRECONDITION_FAILED = 412;

    const STATUS_REQUEST_ENTITY_TOO_LARGE = 413;

    const STATUS_REQUEST_URI_TOO_LONG = 414;

    const STATUS_UNSUPPORTED_MEDIA_TYPE = 415;

    const STATUS_REQUESTED_RANGE_NOT_SATISFIABLE = 416;

    const STATUS_EXPECTATION_FAILED = 417;

    const STATUS_I_AM_A_TEAPOT = 418;

    const STATUS_MISDIRECTED_REQUEST = 421;

    const STATUS_UNPROCESSABLE_ENTITY = 422;

    const STATUS_LOCKED = 423;

    const STATUS_FAILED_DEPENDENCY = 424;

    const STATUS_TOO_EARLY = 425;

    const STATUS_UPGRADE_REQUIRED = 426;

    const STATUS_PRECONDITION_REQUIRED = 428;

    const STATUS_TOO_MANY_REQUESTS = 429;

    const STATUS_REQUEST_HEADER_FIELDS_TOO_LARGE = 431;

    const STATUS_UNAVAILABLE_FOR_LEGAL_REASONS = 451;

    const STATUS_INTERNAL_SERVER_ERROR = 500;

    const STATUS_NOT_IMPLEMENTED = 501;

    const STATUS_BAD_GATEWAY = 502;

    const STATUS_SERVICE_UNAVAILABLE = 503;

    const STATUS_GATEWAY_TIMEOUT = 504;

    const STATUS_VERSION_NOT_SUPPORTED = 505;

    const STATUS_VARIANT_ALSO_NEGOTIATES_EXPERIMENTAL = 506;

    const STATUS_INSUFFICIENT_STORAGE = 507;

    const STATUS_LOOP_DETECTED = 508;

    const STATUS_NOT_EXTENDED = 510;

    const STATUS_NETWORK_AUTHENTICATION_REQUIRED = 511;

    /**
     * Default translations for status codes
     *
     * @var array
     *
     * @link http://www.iana.org/assignments/http-status-codes/ Hypertext Transfer Protocol (HTTP) Status Code Registry
     */
    const STATUS_REASONS = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        103 => 'Early Hints',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        208 => 'Already Reported',
        226 => 'IM Used',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => '', // Unused / Reserved
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Payload Too Large',
        414 => 'URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => "I'm a teapot",
        421 => 'Misdirected Request',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Too Early',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        451 => 'Unavailable For Legal Reasons',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        510 => 'Not Extended',
        511 => 'Network Authentication Required',
    ];

    /**
     * The response status code
     *
     * @var int
     */
    protected $statusCode;

    /**
     * Optionally overridden status reason phrase
     *
     * If not set, the default is used
     *
     * @var string
     */
    protected $reasonPhrase;

    /**
     * Request constructor.
     *
     * @param int $statusCode
     * @param array $headers
     * @param StreamInterface|resource|string|null $body
     * @param string $version
     * @param string $reasonPhrase
     */
    public function __construct(int $statusCode = 200, array $headers = [], $body = null, string $version = '1.1', string $reasonPhrase = '')
    {
        parent::__construct($headers, $body, $version);
        $this->setStatus($statusCode, $reasonPhrase);
    }

    /**
     * {@inheritDoc}
     *
     * @see ResponseInterface::getStatusCode()
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Update the status code.
     *
     * This method DOES NOT retain the immutability of the message like methods from the PSR do.
     *
     * @link http://tools.ietf.org/html/rfc7231#section-6
     * @link http://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
     *
     * @param int $code The 3-digit integer result code to set.
     *
     * @throws InvalidArgumentException For invalid status code arguments.
     */
    public function setStatusCode(int $code)
    {
        if (!in_array($code, array_keys(self::STATUS_REASONS))) {
            throw new InvalidStatusCodeException($code);
        }
        $this->statusCode = $code;
    }

    /**
     * Update the status code.
     *
     * This method DOES retain the immutability of the message like methods from the PSR do.
     *
     * @link http://tools.ietf.org/html/rfc7231#section-6
     * @link http://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
     *
     * @param int $code The 3-digit integer result code to set.
     *
     * @throws InvalidArgumentException For invalid status code arguments.
     *
     * @return self
     */
    public function withStatusCode(int $code)
    {
        if ($code === $this->statusCode) {
            return $this;
        }
        $new = clone $this;
        $new->setStatus($code);
        return $new;
    }

    /**
     * Update the status code and, optionally, reason phrase.
     *
     * If no reason phrase is specified, select the RFC standard reason
     *
     * This method DOES NOT retain the immutability of the message like methods from the PSR do.
     *
     * @link http://tools.ietf.org/html/rfc7231#section-6
     * @link http://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
     *
     * @param int $code The 3-digit integer result code to set.
     * @param string $reasonPhrase The reason phrase to use with the
     *     provided status code; if none is provided, implementations MAY
     *     use the defaults as suggested in the HTTP specification.
     *
     * @throws InvalidArgumentException For invalid status code arguments.
     */
    public function setStatus($code, $reasonPhrase = '')
    {
        $this->setStatusCode($code);
        $this->setReasonPhrase($reasonPhrase);
    }

    /**
     * {@inheritDoc}
     *
     * @see ResponseInterface::withStatus()
     */
    public function withStatus($code, $reasonPhrase = '')
    {
        if ($this->statusCode === $code && $this->reasonPhrase === $reasonPhrase) {
            return $this;
        }
        $new = clone $this;
        $new->setStatus($code, $reasonPhrase);
        return $new;
    }

    /**
     * {@inheritDoc}
     *
     * @see ResponseInterface::getReasonPhrase()
     */
    public function getReasonPhrase()
    {
        return $this->reasonPhrase ?: self::STATUS_REASONS[$this->statusCode];
    }

    /**
     * Update the reason phrase.
     *
     * This method DOES NOT retain the immutability of the message like methods from the PSR do.
     *
     * @param string $reasonPhrase The reason phrase to use with the
     *     provided status code; if none is provided, implementations MAY
     *     use the defaults as suggested in the HTTP specification.
     */
    public function setReasonPhrase(string $reasonPhrase = '')
    {
        $this->reasonPhrase = $reasonPhrase;
    }

    /**
     * Update the reason phrase.
     *
     * This method DOES retain the immutability of the message like methods from the PSR do.
     *
     * @param string $reasonPhrase The reason phrase to use with the
     *     provided status code; if none is provided, implementations MAY
     *     use the defaults as suggested in the HTTP specification.
     *
     * @return self
     */
    public function withReasonPhrase(string $reasonPhrase = '')
    {
        if ($this->reasonPhrase === $reasonPhrase) {
            return $this;
        }
        $new = clone $this;
        $new->setReasonPhrase($reasonPhrase);
        return $new;
    }
}
