<?php

namespace Kusabi\Http\Tests;

class UploadedFileTestCase extends TestCase
{
    public function providesBadErrorCodes(): array
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

    public function providesInvalidDestinationPath(): array
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

    public function providesInvalidFiles(): array
    {
        return [
            'null' => [null],
            'false' => [false],
            'true' => [true],
            'integer' => [1],
            'float' => [1.1],
            'array' => [[]],
            'object' => [(object) []],
        ];
    }

    public function providesValidErrorCodes(): array
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

    /**
     * Return the temporary directory for the current platform
     *
     * @return string
     */
    protected function getTemporaryDirectory(): string
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
    protected function getTemporaryLocation(string $filename): string
    {
        return $this->getTemporaryDirectory().$filename;
    }
}
