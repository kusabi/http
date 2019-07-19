<?php

namespace Tests;

use InvalidArgumentException;
use Kusabi\Http\UploadedFile;
use PHPUnit\Framework\TestCase;

class UploadedFileErrorTest extends TestCase
{
    /**
     * @param int $error
     *
     * @dataProvider validErrorCodeProvider
     */
    public function testValidErrorCodes(int $error)
    {
        $uploadedFile = new UploadedFile('test', 4);
        $uploadedFile->setError($error);
        $this->assertSame($error, $uploadedFile->getError());
    }

    public function testInvalidErrorCodesThrowException()
    {
        $this->expectException(InvalidArgumentException::class);
        $uploadedFile = new UploadedFile('test', 4);
        $uploadedFile->setError(5);
    }

    /**
     * @param int $error
     * @param bool $isOk
     *
     * @dataProvider validErrorCodeProvider
     */
    public function testIsOkMethod(int $error, bool $isOk)
    {
        $uploadedFile = new UploadedFile('test', 4, $error);
        $this->assertSame($uploadedFile->isOk(), $isOk);
    }

    public function validErrorCodeProvider()
    {
        return [
            [UPLOAD_ERR_OK, true],
            [UPLOAD_ERR_INI_SIZE, false],
            [UPLOAD_ERR_FORM_SIZE, false],
            [UPLOAD_ERR_PARTIAL, false],
            [UPLOAD_ERR_NO_FILE, false],
            [UPLOAD_ERR_NO_TMP_DIR, false],
            [UPLOAD_ERR_CANT_WRITE, false],
            [UPLOAD_ERR_EXTENSION, false],
        ];
    }
}
