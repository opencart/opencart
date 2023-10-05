<?php

declare(strict_types = 1);

namespace CodeLts\CliTools\Tests;

use CodeLts\CliTools\File\CouldNotReadFileException;
use CodeLts\CliTools\File\CouldNotWriteFileException;
use CodeLts\CliTools\File\FileReader;
use CodeLts\CliTools\File\FileWriter;

class FileTest extends AbstractTestCase
{

    public function testReadWriteFile(): void
    {
        $fileName = (string) tempnam(sys_get_temp_dir(), 'code-lts-cli-tools');
        FileWriter::write(
            $fileName,
            'foobar'
        );
        $this->assertSame('foobar', FileReader::read($fileName));
        unlink($fileName);
    }

    public function testReadInvalidFile(): void
    {
        $this->expectException(CouldNotReadFileException::class);
        $this->expectExceptionMessage('Could not read file: /');
        $this->assertSame('foobar', FileReader::read('/'));
    }

    public function testWriteInvalidFile(): void
    {
        $this->expectException(CouldNotWriteFileException::class);
        if (PHP_VERSION_ID >= 80000) {
            $this->expectExceptionMessage('Could not write file: / (file_put_contents(/): Failed to open stream: Is a directory)');
        }
        if (PHP_VERSION_ID < 80000) {
            $this->expectExceptionMessage('Could not write file: / (file_put_contents(/): failed to open stream: Is a directory)');
        }
        FileWriter::write('/', '');
    }

}
