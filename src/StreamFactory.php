<?php

namespace Kusabi\Http;

use InvalidArgumentException;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use RuntimeException;

class StreamFactory implements StreamFactoryInterface
{
    /**
     * {@inheritDoc}
     *
     * @return Stream
     *
     * @see StreamFactoryInterface::createStream()
     */
    public function createStream(string $content = ''): StreamInterface
    {
        $stream = static::createStreamFromResource(
            fopen('php://temp', 'w+')
        );
        $stream->write($content);
        $stream->rewind();
        return $stream;
    }

    /**
     * Attempt to create a stream based on any parameter value
     *
     * @param mixed $value
     *
     * @throws InvalidArgumentException if cannot create stream from data
     *
     * @return StreamInterface
     */
    public function createStreamFromAny($value): StreamInterface
    {
        if (is_string($value) && is_file($value)) {
            return $this->createStreamFromFile($value);
        } elseif (is_string($value)) {
            return $this->createStream($value);
        } elseif (is_resource($value)) {
            return $this->createStreamFromResource($value);
        } elseif ($value instanceof StreamInterface) {
            return $value;
        }
        throw new InvalidArgumentException('Could not create a stream from data');
    }

    /**
     * {@inheritDoc}
     *
     * @return Stream
     *
     * @see StreamFactoryInterface::createStreamFromFile()
     */
    public function createStreamFromFile(string $filename, string $mode = 'r'): StreamInterface
    {
        $resource = @fopen($filename, $mode);
        if ($resource === false) {
            throw new RuntimeException('File could not be opened');
        }
        return static::createStreamFromResource($resource);
    }

    /**
     * {@inheritDoc}
     *
     * @return Stream
     *
     * @see StreamFactoryInterface::createStreamFromResource()
     */
    public function createStreamFromResource($resource): StreamInterface
    {
        return new Stream($resource);
    }
}
