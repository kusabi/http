[![Build Badge](https://img.shields.io/circleci/build/gh/kusabi/stream/master.svg)](https://img.shields.io/circleci/build/gh/kusabi/stream/master.svg)
[![Release Badge](https://img.shields.io/github/release/kusabi/stream.svg)](https://img.shields.io/github/release/kusabi/uri.svg)
[![Tag Badge](https://img.shields.io/github/tag/kusabi/stream.svg)](https://img.shields.io/github/tag/kusabi/uri.svg)
[![Coverage Badge](https://img.shields.io/codacy/coverage/b0465ef64f3643a8a8cdb5453eea9274.svg)](https://img.shields.io/codacy/grade/b0465ef64f3643a8a8cdb5453eea9274.svg)
[![Grade Badge](https://img.shields.io/codacy/grade/b0465ef64f3643a8a8cdb5453eea9274.svg?label=quality)](https://img.shields.io/codacy/grade/b0465ef64f3643a8a8cdb5453eea9274.svg)
[![Issues Badge](https://img.shields.io/github/issues/kusabi/stream.svg)](https://img.shields.io/github/issues/kusabi/uri.svg)
[![Licence Badge](https://img.shields.io/github/license/kusabi/stream.svg)](https://img.shields.io/github/license/kusabi/uri.svg)
[![Code Size](https://img.shields.io/github/languages/code-size/kusabi/stream.svg?label=size)](https://img.shields.io/github/languages/code-size/kusabi/uri.svg)

An implementation of a [PSR-7](https://www.php-fig.org/psr/psr-7/) & [PSR-17](https://www.php-fig.org/psr/psr-17/) conforming Stream library

# Installation

Installation is simple using composer.

```bash
composer require kusabi/stream
```

Or simply add it to your `composer.json` file

```json
{
    "require": {
        "kusabi/stream": "^1.0"
    }
}
```

# Using the Stream class

The Stream class is a very basic wrapper around a stream resource.


```php
use Kusabi\Stream\Stream;

// Instantiate a Uri instance
$stream = new Stream(fopen('php://stdin', 'r'));

// Fetch the properties of the Stream
$stream->getContents(); // Get everything from the current pointer to the end of the stream
$stream->getSize(); // Get the size of the stream in bytes
$stream->isSeekable();
$stream->isReadable();
$stream->isWritable();
$stream->seek($offset, $whence = SEEK_SET); // Move the pointer around in the stream
$stream->tell(); // Where is the pointer in the stream
$stream->rewind(); // Set the pointer to the beginning of the stream
$stream->read($length); // Read the next $length character from the stream
$stream->write($string); // Write data into the stream. Returns the number of bytes written
$stream->getMetadata($key = null); // Get all the metadata, or a particular key
$stream->getStat($key = null); // Get all the fstat entries, or a particular key
$stream->isLocal(); // Determine if the stream url is local using `stream_is_local()`
$stream->getLine($length = null, $ending = "\n"); // Fetch a line up to a length or delimiter (which ever comes first)
$stream->pipe(Stream $stream); // Copy the contents of one stream into another
(string) $stream; // Rewind and get all the contents from the stream

```


# Using the Stream Factory

The Stream factory can be used to create the Stream instance too.


```php
use Kusabi\Stream\StreamFactory;

// Instantiate a Uri instance
$factory = new StreamFactory();
$stream = $factory->createStream('temp resource with data in it');
$stream = $factory->createStreamFromFile('file.txt');
$stream = $factory->createStreamFromResource(fopen('php://stdin', 'r'));
```