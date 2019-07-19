<?php

namespace Kusabi\Http\Exceptions;

use InvalidArgumentException;
use Throwable;

class InvalidUploadedFileStatusException extends InvalidArgumentException
{
    public function __construct($status, $code = 0, Throwable $previous = null)
    {
        parent::__construct("Invalid status '{$status}' for an uploaded file. Value must be one of PHPs UPLOAD_ERR_XXX constants", $code, $previous);
    }
}
