<?php

namespace Kusabi\Http\Exceptions;

use InvalidArgumentException;
use Throwable;

/**
 * The port is not within a valid UDP/TCP range
 *
 * @author Christian Harvey <kusabi.software@gmail.com>
 */
class InvalidPortException extends InvalidArgumentException
{
    public function __construct(string $port = '', int $code = 0, Throwable $previous = null)
    {
        parent::__construct("Port '{$port}' is not within a valid UDP/TCP range", $code, $previous);
    }
}
