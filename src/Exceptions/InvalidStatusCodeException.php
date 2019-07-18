<?php

namespace Kusabi\Http\Exceptions;

use InvalidArgumentException;
use Throwable;

class InvalidStatusCodeException extends InvalidArgumentException
{
    public function __construct($statusCode, $code = 0, Throwable $previous = null)
    {
        parent::__construct("Invalid HTTP response status code '{$statusCode}' was provided", $code, $previous);
    }
}
