<?php

namespace Kusabi\Http\Exceptions;

use InvalidArgumentException;
use Throwable;

class InvalidUriException extends InvalidArgumentException
{
    public function __construct(string $uri = '', int $code = 0, Throwable $previous = null)
    {
        parent::__construct("The uri '{$uri}' is not valid", $code, $previous);
    }
}
