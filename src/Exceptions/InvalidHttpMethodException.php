<?php

namespace Kusabi\Http\Exceptions;

use InvalidArgumentException;
use Throwable;

class InvalidHttpMethodException extends InvalidArgumentException
{
    public function __construct($method, $code = 0, Throwable $previous = null)
    {
        parent::__construct("Invalid HTTP method '{$method}' was provided", $code, $previous);
    }
}
