<?php

namespace Kusabi\Http\Tests\UploadedFile;

use Kusabi\Http\Tests\UploadedFileTestCase;
use Kusabi\Http\UploadedFile;

class Creating extends UploadedFileTestCase
{
    /**
     * @covers \Kusabi\Http\UploadedFile::__construct
     */
    public function testCreateWithString()
    {
        $uploadedFile = new UploadedFile('test', 4, 0, 'test.txt', 'txt');
        $this->assertSame('test.txt', $uploadedFile->getClientFilename());
    }

    /**
     * @covers \Kusabi\Http\UploadedFile::__construct
     */
    public function testCreateWithFile()
    {
        $uploadedFile = new UploadedFile(__DIR__.'/readable_file.txt', 4, 0, 'test.txt', 'txt');
        $this->assertSame('test.txt', $uploadedFile->getClientFilename());
    }
}
