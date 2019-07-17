<?php

namespace Kusabi\Http\Exceptions;

use InvalidArgumentException;
use Throwable;

class InvalidHeaderKeyException extends InvalidArgumentException
{
    public function __construct($name, $code = 0, Throwable $previous = null)
    {
        $type = is_object($name) ? get_class($name) : gettype($name);
        if ($type === 'string' && $name === '') {
            $type = 'empty string';
        }
        parent::__construct("Headers must be a non-empty string but '<{$type}>' was provided", $code, $previous);
    }
}
