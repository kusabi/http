<?php

namespace Tests;

use Kusabi\Http\UploadedFile;
use PHPUnit\Framework\TestCase;

class UploadedFileClientTest extends TestCase
{
    public function testClientFilename()
    {
        $uploadedFile = new UploadedFile('test', 4, 0, 'test.txt', 'txt');
        $this->assertSame('test.txt', $uploadedFile->getClientFilename());
    }

    public function testClientFilenameIsEmptyWhenNotProvided()
    {
        $uploadedFile = new UploadedFile('test', 4);
        $this->assertSame('', $uploadedFile->getClientFilename());
    }

    public function testClientMediaType()
    {
        $uploadedFile = new UploadedFile('test', 4, 0, 'test.txt', 'txt');
        $this->assertSame('txt', $uploadedFile->getClientMediaType());
    }

    public function testClientMediaTypeIsEmptyWhenNotProvided()
    {
        $uploadedFile = new UploadedFile('test', 4);
        $this->assertSame('', $uploadedFile->getClientMediaType());
    }
}
