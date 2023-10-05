<?php

declare(strict_types = 1);

/*
 * (c) Copyright (c) 2016-2020 Ondřej Mirtes <ondrej@mirtes.cz>
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

namespace CodeLts\CliTools\Tests\ErrorFormatter;

use CodeLts\CliTools\ErrorFormatter\TableErrorFormatter;
use CodeLts\CliTools\File\FuzzyRelativePathHelper;
use CodeLts\CliTools\File\NullRelativePathHelper;
use CodeLts\CliTools\Tests\ErrorFormatterTestCase;

class TableErrorFormatterTest extends ErrorFormatterTestCase
{

    /**
     * @return iterable<array>
     */
    public function dataFormatterOutputProvider(): iterable
    {
        $dashLine = $this->getTableDecoration();

        yield [
            'No errors',
            0,
            0,
            0,
            '
 [OK] No errors

',
        ];

        yield [
            'One file error',
            1,
            1,
            0,
            ' ' . $dashLine . '
  Line   folder with unicode 😃/file name with "spaces" and unicode 😃.php
 ' . $dashLine . '
  4      Foo
 ' . $dashLine . $this->getEndTable() . '

 [ERROR] Found 1 error

',
        ];

        yield [
            'One generic error',
            1,
            0,
            1,
            ' -- ---------------------
     Error
 -- ---------------------
     first generic error
 -- ---------------------
' . $this->getEndTable() . '
 [ERROR] Found 1 error

',
        ];

        yield [
            'Multiple file errors',
            1,
            4,
            0,
            ' ' . $dashLine . '
  Line   folder with unicode 😃/file name with "spaces" and unicode 😃.php
 ' . $dashLine . '
  2      Bar
         Bar2
  4      Foo
 ' . $dashLine . '

 ------ ---------
  Line   foo.php
 ------ ---------
  1      Foo
  5      Bar
         Bar2
 ------ ---------

 [ERROR] Found 4 errors

',
        ];

        yield [
            'Multiple generic errors',
            1,
            0,
            2,
            ' -- ----------------------
     Error
 -- ----------------------
     first generic error
     second generic error
 -- ----------------------
' . $this->getEndTable() . '
 [ERROR] Found 2 errors

',
        ];

        yield [
            'Multiple file, multiple generic errors',
            1,
            4,
            2,
            ' ' . $dashLine . '
  Line   folder with unicode 😃/file name with "spaces" and unicode 😃.php
 ' . $dashLine . '
  2      Bar
         Bar2
  4      Foo
 ' . $dashLine . '

 ------ ---------
  Line   foo.php
 ------ ---------
  1      Foo
  5      Bar
         Bar2
 ------ ---------

 -- ----------------------
     Error
 -- ----------------------
     first generic error
     second generic error
 -- ----------------------

 [ERROR] Found 6 errors

',
        ];
    }

    /**
     * @dataProvider dataFormatterOutputProvider
     *
     * @param string $message
     * @param int    $exitCode
     * @param int    $numFileErrors
     * @param int    $numGenericErrors
     * @param string $expected
     */
    public function testFormatErrors(
        string $message,
        int $exitCode,
        int $numFileErrors,
        int $numGenericErrors,
        string $expected
    ): void {
        $formatter = new TableErrorFormatter(new FuzzyRelativePathHelper(new NullRelativePathHelper(), self::DIRECTORY_PATH));

        $this->assertSame(
            $exitCode,
            $formatter->formatErrors(
                $this->getAnalysisResult($numFileErrors, $numGenericErrors),
                $this->getOutput()
            ),
            sprintf('%s: response code do not match', $message)
        );

        $this->assertEquals($expected, $this->getOutputContent(), sprintf('%s: output do not match', $message));
    }

}
