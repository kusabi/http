<?php

namespace Kusabi\Http;

use InvalidArgumentException;
use Kusabi\Http\Exceptions\InvalidUploadedFileStatusException;
use Kusabi\Http\Exceptions\UploadedFileAlreadyMovedException;
use Kusabi\Stream\Stream;
use Kusabi\Stream\StreamFactory;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;
use RuntimeException;

class UploadedFile implements UploadedFileInterface
{
    /**
     * A list of valid error codes
     *
     * @var array
     */
    const VALID_ERRORS = [
        UPLOAD_ERR_OK,
        UPLOAD_ERR_INI_SIZE,
        UPLOAD_ERR_FORM_SIZE,
        UPLOAD_ERR_PARTIAL,
        UPLOAD_ERR_NO_FILE,
        UPLOAD_ERR_NO_TMP_DIR,
        UPLOAD_ERR_CANT_WRITE,
        UPLOAD_ERR_EXTENSION,
    ];

    /**
     * The file stream (if provided)
     *
     * @var Stream
     */
    protected $stream;

    /**
     * The file name (if provided)
     *
     * @var string
     */
    protected $filename;

    /**
     * The size of the file as supplied from the $_FILES super global
     *
     * @var int
     */
    protected $size;

    /**
     * The error code for the file
     *
     * @var int
     */
    protected $error = UPLOAD_ERR_OK;

    /**
     * Has the uploaded file moved moved yet?
     *
     * @var bool
     */
    protected $moved = false;

    /**
     * The client filename provided by the browser
     *
     * @var string
     */
    protected $clientFilename;

    /**
     * The client media type provided by the browser
     *
     * @var string
     */
    protected $clientMediaType;

    /**
     * The stream factory
     *
     * @var StreamFactory
     */
    protected $streamFactory;

    public function __construct($fileOrStream, int $size, int $error = UPLOAD_ERR_OK, $clientFilename = '', $clientMediaType = '')
    {
        $this->streamFactory = new StreamFactory();
        if ($this->isOk()) {
            if (is_string($fileOrStream) && file_exists($fileOrStream)) {
                $this->filename = $fileOrStream;
            } else {
                $this->stream = $this->createStreamFromAny($fileOrStream);
            }
        }
        $this->size = $size;
        $this->setError($error);
        $this->clientFilename = $clientFilename;
        $this->clientMediaType = $clientMediaType;
    }

    /**
     * {@inheritDoc}
     *
     * @see UploadedFileInterface::getStream()
     *
     * @return Stream
     */
    public function getStream()
    {
        // File has already been moved!
        if ($this->isMoved()) {
            throw new UploadedFileAlreadyMovedException('Uploaded file has already been moved');
        }
        if ($this->stream instanceof StreamInterface) {
            return $this->stream;
        }
        return $this->streamFactory->createStreamFromFile($this->filename);
    }

    /**
     * {@inheritDoc}
     *
     * @see UploadedFileInterface::moveTo()
     */
    public function moveTo($targetPath)
    {
        // File has already been moved!
        if (!$this->isOk()) {
            throw new RuntimeException('There was an error uploading the file');
        }

        // File has already been moved!
        if ($this->isMoved()) {
            throw new UploadedFileAlreadyMovedException('Uploaded file has already been moved');
        }

        // Destination is valid
        if (!is_string($targetPath) || empty($targetPath)) {
            throw new InvalidArgumentException('Destination path MUST be a non-empty string');
        }

        // If file, use the file methods
        $this->moved = false;
        if ($this->filename && php_sapi_name() == 'cli') {
            $this->moved = rename($this->filename, $targetPath);
        } elseif ($this->filename) {
            // @codeCoverageIgnoreStart
            $this->moved = move_uploaded_file($this->filename, $targetPath);
        // @codeCoverageIgnoreEnd
        } else {
            stream_copy_to_stream(
                $this->getStream()->getResource(),
                $this->streamFactory->createStreamFromFile($targetPath, 'w+')->getResource()
            );
            $this->moved = true;
        }

        if (!$this->isMoved()) {
            // @codeCoverageIgnoreStart
            throw new RuntimeException("Uploaded file could not be moved to '{$targetPath}'");
            // @codeCoverageIgnoreEnd
        }
    }

    /**
     * {@inheritDoc}
     *
     * @see UploadedFileInterface::getSize()
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * {@inheritDoc}
     *
     * @see UploadedFileInterface::getError()
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Set the error associated with the uploaded file.
     *
     * The  value MUST be one of PHPs UPLOAD_ERR_XXX constants.
     *
     * @param int $error
     *
     * @see http://php.net/manual/en/features.file-upload.errors.php
     *
     * @throws InvalidUploadedFileStatusException
     */
    public function setError(int $error)
    {
        if (!in_array($error, self::VALID_ERRORS)) {
            throw new InvalidUploadedFileStatusException($error);
        }
        $this->error = $error;
    }

    /**
     * {@inheritDoc}
     *
     * @see UploadedFileInterface::getClientFilename()
     */
    public function getClientFilename()
    {
        return $this->clientFilename;
    }

    /**
     * {@inheritDoc}
     *
     * @see UploadedFileInterface::getClientMediaType()
     */
    public function getClientMediaType()
    {
        return $this->clientMediaType;
    }

    /**
     * Was the file upload OK?
     *
     * @return bool
     */
    public function isOk()
    {
        return $this->error === UPLOAD_ERR_OK;
    }

    /**
     * Has the file upload been moved yet?
     *
     * @return bool
     */
    public function isMoved()
    {
        return $this->moved;
    }

    /**
     * Create a stream from whatever was passed in as a filename
     *
     * @param mixed $value
     *
     * @throws InvalidArgumentException if cannot create stream from data
     *
     * @return StreamInterface
     *
     */
    protected function createStreamFromAny($value) : StreamInterface
    {
        if (is_string($value) && is_file($value)) {
            // @codeCoverageIgnoreStart
            return $this->streamFactory->createStreamFromFile($value);
        // @codeCoverageIgnoreEnd
        } elseif (is_string($value)) {
            return $this->streamFactory->createStream($value);
        } elseif (is_resource($value)) {
            return $this->streamFactory->createStreamFromResource($value);
        } elseif ($value instanceof StreamInterface) {
            return $value;
        } else {
            throw new InvalidArgumentException(
                'Could not create a stream from data'
            );
        }
    }
}
