<?php

namespace Kusabi\Http\Tests\UploadedFile;

use InvalidArgumentException;
use Kusabi\Http\Tests\UploadedFileTestCase;
use Kusabi\Http\UploadedFile;
use RuntimeException;

class Modifying extends UploadedFileTestCase
{
    /**
     * @param int $error
     *
     * @dataProvider providesBadErrorCodes
     *
     * @covers \Kusabi\Http\UploadedFile::moveTo
     */
    public function testCannotMoveIfFileError(int $error)
    {
        $this->expectException(RuntimeException::class);
        $uploadTo = $this->getTemporaryLocation('uploadedFile.txt');
        $uploadedFile = new UploadedFile('test', 4, $error);
        $uploadedFile->moveTo($uploadTo);
    }

    /**
     * @covers \Kusabi\Http\UploadedFile::moveTo
     */
    public function testFileCanBeMoved()
    {
        $uploadTo = $this->getTemporaryLocation('uploadedFile.txt');
        $uploadedFile = new UploadedFile('test', 4);
        $uploadedFile->moveTo($uploadTo);
        $this->assertFileExists($uploadTo);
        $this->assertSame('test', file_get_contents($uploadTo));
    }

    /**
     * @covers \Kusabi\Http\UploadedFile::moveTo
     */
    public function testFileCanBeMovedWithFileUpload()
    {
        $tmpFile = tempnam($this->getTemporaryDirectory(), 'uploading');
        $handle = fopen($tmpFile, 'w+');
        fwrite($handle, 'test');
        fclose($handle);
        $uploadTo = $this->getTemporaryLocation('uploadedFile.txt');
        $uploadedFile = new UploadedFile($tmpFile, 4);
        $uploadedFile->moveTo($uploadTo);
        $this->assertFileExists($uploadTo);
        $this->assertSame('test', file_get_contents($uploadTo));
    }

    /**
     * @covers \Kusabi\Http\UploadedFile::setError
     */
    public function testInvalidErrorCodesThrowException()
    {
        $this->expectException(InvalidArgumentException::class);
        $uploadedFile = new UploadedFile('test', 4);
        $uploadedFile->setError(5);
    }

    /**
     * @covers \Kusabi\Http\UploadedFile::moveTo
     */
    public function testMoveCannotBeCalledTwice()
    {
        $this->expectException(RuntimeException::class);
        $uploadTo = $this->getTemporaryLocation('uploadedFile.txt');
        $uploadedFile = new UploadedFile('test', 4);
        $uploadedFile->moveTo($uploadTo);
        $uploadedFile->moveTo($uploadTo);
    }

    /**
     * @param mixed $destination
     *
     * @dataProvider providesInvalidDestinationPath
     *
     * @covers \Kusabi\Http\UploadedFile::moveTo
     */
    public function testMoveThrowsExceptionForInvalidDestinations($destination)
    {
        $this->expectException(InvalidArgumentException::class);
        $uploadedFile = new UploadedFile('test', 4);
        $uploadedFile->moveTo($destination);
    }

    /**
     * @covers \Kusabi\Http\UploadedFile::moveTo
     * @covers \Kusabi\Http\UploadedFile::getStream
     */
    public function testStreamCannotBeRetrievedAfterMove()
    {
        $this->expectException(RuntimeException::class);
        $uploadTo = $this->getTemporaryLocation('uploadedFile.txt');
        $uploadedFile = new UploadedFile('test', 4);
        $uploadedFile->moveTo($uploadTo);
        $uploadedFile->getStream();
    }

    /**
     * @param int $error
     *
     * @dataProvider providesValidErrorCodes
     *
     * @covers \Kusabi\Http\UploadedFile::setError
     * @covers \Kusabi\Http\UploadedFile::getError
     */
    public function testValidErrorCodes(int $error)
    {
        $uploadedFile = new UploadedFile('test', 4);
        $uploadedFile->setError($error);
        $this->assertSame($error, $uploadedFile->getError());
    }
}
