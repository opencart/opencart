<?php

declare(strict_types = 1);

/*
 * (c) Copyright (c) 2016-2021 Ondřej Mirtes <ondrej@mirtes.cz>
 *
 * This source file is subject to the MIT license.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace CodeLts\CliTools\Tests;

use CodeLts\CliTools\Error;
use CodeLts\CliTools\AnalysisResult;
use CodeLts\CliTools\ErrorsConsoleStyle;
use CodeLts\CliTools\Output;
use CodeLts\CliTools\Symfony\SymfonyOutput;
use CodeLts\CliTools\Symfony\SymfonyStyle;
use Exception;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\StreamOutput;

abstract class ErrorFormatterTestCase extends \CodeLts\CliTools\Tests\AbstractTestCase
{

    protected const DIRECTORY_PATH = '/data/folder/with space/and unicode 😃/project';

    /** @var StreamOutput|null */
    private $outputStream = null;

    /** @var Output|null */
    private $output = null;

    protected function getEndTable(): string
    {
        if (PHP_MAJOR_VERSION === 7 && PHP_MINOR_VERSION === 1) {
            return '';
        }

        return "\n";
    }

    protected function getTableDecoration(): string
    {
        if (PHP_MAJOR_VERSION === 7 && PHP_MINOR_VERSION === 1) {
            return '------ -----------------------------------------------------------------';
        }

        return '------ -------------------------------------------------------------------';
    }

    private function getOutputStream(): StreamOutput
    {
        if (PHP_VERSION_ID >= 80000 && DIRECTORY_SEPARATOR === '\\') {
            $this->markTestSkipped('Skipped because of https://github.com/symfony/symfony/issues/37508');
        }
        if ($this->outputStream === null) {
            $resource = fopen('php://memory', 'w', false);
            if ($resource === false) {
                throw new Exception('This should not happen');
            }
            $this->outputStream = new StreamOutput($resource);
        }

        return $this->outputStream;
    }

    protected function getOutput(): Output
    {
        if ($this->output === null) {
            $errorConsoleStyle = new ErrorsConsoleStyle(new StringInput(''), $this->getOutputStream());
            $this->output      = new SymfonyOutput($this->getOutputStream(), new SymfonyStyle($errorConsoleStyle));
        }

        return $this->output;
    }

    protected function getOutputContent(): string
    {
        rewind($this->getOutputStream()->getStream());

        $contents = stream_get_contents($this->getOutputStream()->getStream());
        if ($contents === false) {
            throw new Exception('This should not happen');
        }

        return $this->rtrimMultiline($contents);
    }

    protected function getAnalysisResult(int $numFileErrors, int $numGenericErrors, int $numWarnings = 0): AnalysisResult
    {
        if ($numFileErrors > 5 || $numFileErrors < 0 || $numGenericErrors > 2 || $numGenericErrors < 0) {
            throw new \Exception('Test case error');
        }

        $fileErrors = array_slice(
            [
            new Error('Foo', self::DIRECTORY_PATH . '/folder with unicode 😃/file name with "spaces" and unicode 😃.php', 4),
            new Error('Foo', self::DIRECTORY_PATH . '/foo.php', 1),
            new Error("Bar\nBar2", self::DIRECTORY_PATH . '/foo.php', 5),
            new Error("Bar\nBar2", self::DIRECTORY_PATH . '/folder with unicode 😃/file name with "spaces" and unicode 😃.php', 2),
            new Error("Bar\nBar2", self::DIRECTORY_PATH . '/foo.php', null),
            ],
            0,
            $numFileErrors
        );

        $genericErrors = array_slice(
            [
            'first generic error',
            'second generic error',
            ],
            0,
            $numGenericErrors
        );

        $warnings = array_slice(
            [
            'first warning',
            'second 😃 warning',
            '3rd warning !',
            ],
            0,
            $numWarnings
        );

        return new AnalysisResult(
            $fileErrors,
            $genericErrors,
            [],
            $warnings
        );
    }

    private function rtrimMultiline(string $output): string
    {
        $result = array_map(
            static function (string $line): string {
                return rtrim($line, " \r\n");
            },
            explode("\n", $output)
        );

        return implode("\n", $result);
    }

}
