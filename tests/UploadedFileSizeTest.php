<?php

namespace Tests;

use Kusabi\Http\UploadedFile;
use PHPUnit\Framework\TestCase;

class UploadedFileSizeTest extends TestCase
{
    public function testSize()
    {
        $uploadedFile = new UploadedFile('test', 4);
        $this->assertSame(4, $uploadedFile->getSize());
    }
}
