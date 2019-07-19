<?php

namespace Tests;

use InvalidArgumentException;
use Kusabi\Http\UploadedFile;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class UploadedFileMoveTest extends TestCase
{
    public function testFileCanBeMoved()
    {
        $uploadTo = $this->getTemporaryLocation('uploadedFile.txt');
        $uploadedFile = new UploadedFile('test', 4);
        $uploadedFile->moveTo($uploadTo);
        $this->assertFileExists($uploadTo);
        $this->assertSame('test', file_get_contents($uploadTo));
    }

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
     * @param mixed $destination
     *
     * @dataProvider invalidDestinationPathProvider
     */
    public function testMoveThrowsExceptionForInvalidDestinations($destination)
    {
        $this->expectException(InvalidArgumentException::class);
        $uploadedFile = new UploadedFile('test', 4);
        $uploadedFile->moveTo($destination);
    }

    public function testMoveCannotBeCalledTwice()
    {
        $this->expectException(RuntimeException::class);
        $uploadTo = $this->getTemporaryLocation('uploadedFile.txt');
        $uploadedFile = new UploadedFile('test', 4);
        $uploadedFile->moveTo($uploadTo);
        $uploadedFile->moveTo($uploadTo);
    }

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
     * @dataProvider badErrorCodeProvider
     */
    public function testCannotMoveIfFileError(int $error)
    {
        $this->expectException(RuntimeException::class);
        $uploadTo = $this->getTemporaryLocation('uploadedFile.txt');
        $uploadedFile = new UploadedFile('test', 4, $error);
        $uploadedFile->moveTo($uploadTo);
    }

    public function invalidDestinationPathProvider()
    {
        return [
            'null' => [null],
            'false' => [false],
            'true' => [true],
            'empty' => [''],
            'integer' => [1],
            'float' => [1.1],
            'array' => [[]],
            'object' => [(object) []],
        ];
    }

    public function badErrorCodeProvider()
    {
        return [
            [UPLOAD_ERR_INI_SIZE],
            [UPLOAD_ERR_FORM_SIZE],
            [UPLOAD_ERR_PARTIAL],
            [UPLOAD_ERR_NO_FILE],
            [UPLOAD_ERR_NO_TMP_DIR],
            [UPLOAD_ERR_CANT_WRITE],
            [UPLOAD_ERR_EXTENSION]
        ];
    }

    /**
     * Return the temporary directory for the current platform
     *
     * @return string
     */
    protected function getTemporaryDirectory() : string
    {
        $directory = rtrim(sys_get_temp_dir(), '/\\');
        $directory .= DIRECTORY_SEPARATOR;
        return $directory;
    }

    /**
     * Return the temporary location of a file
     *
     * @param string $filename
     *
     * @return string
     */
    protected function getTemporaryLocation(string $filename) : string
    {
        return $this->getTemporaryDirectory().$filename;
    }
}
