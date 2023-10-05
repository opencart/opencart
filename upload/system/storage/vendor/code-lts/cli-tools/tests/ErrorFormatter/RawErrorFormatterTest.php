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

use CodeLts\CliTools\ErrorFormatter\RawErrorFormatter;
use CodeLts\CliTools\Tests\ErrorFormatterTestCase;

class RawErrorFormatterTest extends ErrorFormatterTestCase
{

    /**
     * @return iterable<array>
     */
    public function dataFormatterOutputProvider(): iterable
    {
        yield [
            'No errors',
            0,
            0,
            0,
            '',
        ];

        yield [
            'One file error',
            1,
            1,
            0,
            '/data/folder/with space/and unicode 😃/project/folder with unicode 😃/file name with "spaces" and unicode 😃.php:4:Foo' . "\n",
        ];

        yield [
            'One generic error',
            1,
            0,
            1,
            '?:?:first generic error' . "\n",
        ];

        yield [
            'Multiple file errors',
            1,
            4,
            0,
            '/data/folder/with space/and unicode 😃/project/folder with unicode 😃/file name with "spaces" and unicode 😃.php:2:Bar' . "\nBar2\n" .
            '/data/folder/with space/and unicode 😃/project/folder with unicode 😃/file name with "spaces" and unicode 😃.php:4:Foo' . "\n" .
            '/data/folder/with space/and unicode 😃/project/foo.php:1:Foo' . "\n" .
            '/data/folder/with space/and unicode 😃/project/foo.php:5:Bar' . "\nBar2\n",
        ];

        yield [
            'Multiple generic errors',
            1,
            0,
            2,
            '?:?:first generic error' . "\n" .
            '?:?:second generic error' . "\n",
        ];

        yield [
            'Multiple file, multiple generic errors',
            1,
            4,
            2,
            '?:?:first generic error' . "\n" .
        '?:?:second generic error' . "\n" .
        '/data/folder/with space/and unicode 😃/project/folder with unicode 😃/file name with "spaces" and unicode 😃.php:2:Bar' . "\nBar2\n" .
        '/data/folder/with space/and unicode 😃/project/folder with unicode 😃/file name with "spaces" and unicode 😃.php:4:Foo' . "\n" .
        '/data/folder/with space/and unicode 😃/project/foo.php:1:Foo' . "\n" .
        '/data/folder/with space/and unicode 😃/project/foo.php:5:Bar' . "\nBar2\n",
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
        $formatter = new RawErrorFormatter();

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
