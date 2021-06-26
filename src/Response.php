<?php

namespace Kusabi\Http;

use InvalidArgumentException;
use Kusabi\Http\Exceptions\InvalidHttpStatusCodeException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class Response extends Message implements ResponseInterface
{
    public const STATUS_CONTINUE = 100;

    public const STATUS_SWITCHING_PROTOCOLS = 101;

    public const STATUS_PROCESSING = 102;

    public const STATUS_EARLY_HINTS = 103;

    public const STATUS_OK = 200;

    public const STATUS_CREATED = 201;

    public const STATUS_ACCEPTED = 202;

    public const STATUS_NON_AUTHORITATIVE_INFORMATION = 203;

    public const STATUS_NO_CONTENT = 204;

    public const STATUS_RESET_CONTENT = 205;

    public const STATUS_PARTIAL_CONTENT = 206;

    public const STATUS_MULTI_STATUS = 207;

    public const STATUS_ALREADY_REPORTED = 208;

    public const STATUS_IM_USED = 226;

    public const STATUS_MULTIPLE_CHOICES = 300;

    public const STATUS_MOVED_PERMANENTLY = 301;

    public const STATUS_FOUND = 302;

    public const STATUS_SEE_OTHER = 303;

    public const STATUS_NOT_MODIFIED = 304;

    public const STATUS_USE_PROXY = 305;

    public const STATUS_RESERVED = 306;

    public const STATUS_TEMPORARY_REDIRECT = 307;

    public const STATUS_PERMANENTLY_REDIRECT = 308;

    public const STATUS_BAD_REQUEST = 400;

    public const STATUS_UNAUTHORIZED = 401;

    public const STATUS_PAYMENT_REQUIRED = 402;

    public const STATUS_FORBIDDEN = 403;

    public const STATUS_NOT_FOUND = 404;

    public const STATUS_METHOD_NOT_ALLOWED = 405;

    public const STATUS_NOT_ACCEPTABLE = 406;

    public const STATUS_PROXY_AUTHENTICATION_REQUIRED = 407;

    public const STATUS_REQUEST_TIMEOUT = 408;

    public const STATUS_CONFLICT = 409;

    public const STATUS_GONE = 410;

    public const STATUS_LENGTH_REQUIRED = 411;

    public const STATUS_PRECONDITION_FAILED = 412;

    public const STATUS_REQUEST_ENTITY_TOO_LARGE = 413;

    public const STATUS_REQUEST_URI_TOO_LONG = 414;

    public const STATUS_UNSUPPORTED_MEDIA_TYPE = 415;

    public const STATUS_REQUESTED_RANGE_NOT_SATISFIABLE = 416;

    public const STATUS_EXPECTATION_FAILED = 417;

    public const STATUS_I_AM_A_TEAPOT = 418;

    public const STATUS_MISDIRECTED_REQUEST = 421;

    public const STATUS_UNPROCESSABLE_ENTITY = 422;

    public const STATUS_LOCKED = 423;

    public const STATUS_FAILED_DEPENDENCY = 424;

    public const STATUS_TOO_EARLY = 425;

    public const STATUS_UPGRADE_REQUIRED = 426;

    public const STATUS_PRECONDITION_REQUIRED = 428;

    public const STATUS_TOO_MANY_REQUESTS = 429;

    public const STATUS_REQUEST_HEADER_FIELDS_TOO_LARGE = 431;

    public const STATUS_UNAVAILABLE_FOR_LEGAL_REASONS = 451;

    public const STATUS_INTERNAL_SERVER_ERROR = 500;

    public const STATUS_NOT_IMPLEMENTED = 501;

    public const STATUS_BAD_GATEWAY = 502;

    public const STATUS_SERVICE_UNAVAILABLE = 503;

    public const STATUS_GATEWAY_TIMEOUT = 504;

    public const STATUS_VERSION_NOT_SUPPORTED = 505;

    public const STATUS_VARIANT_ALSO_NEGOTIATES_EXPERIMENTAL = 506;

    public const STATUS_INSUFFICIENT_STORAGE = 507;

    public const STATUS_LOOP_DETECTED = 508;

    public const STATUS_NOT_EXTENDED = 510;

    public const STATUS_NETWORK_AUTHENTICATION_REQUIRED = 511;

    /**
     * Default translations for status codes
     *
     * @var array
     *
     * @link http://www.iana.org/assignments/http-status-codes/ Hypertext Transfer Protocol (HTTP) Status Code Registry
     */
    public const STATUS_REASONS = [
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
            throw new InvalidHttpStatusCodeException($code);
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
