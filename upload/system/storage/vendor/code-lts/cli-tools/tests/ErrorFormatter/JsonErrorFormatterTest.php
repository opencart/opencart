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

use CodeLts\CliTools\ErrorFormatter\JsonErrorFormatter;
use CodeLts\CliTools\Tests\ErrorFormatterTestCase;

class JsonErrorFormatterTest extends ErrorFormatterTestCase
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
            '
{
	"totals":{
		"errors":0,
		"file_errors":0
	},
	"files":[],
	"errors": []
}',
        ];

        yield [
            'One file error',
            1,
            1,
            0,
            '
{
	"totals":{
		"errors":0,
		"file_errors":1
	},
	"files":{
		"/data/folder/with space/and unicode 😃/project/folder with unicode 😃/file name with \"spaces\" and unicode 😃.php":{
			"errors":1,
			"messages":[
				{
					"message": "Foo",
					"line": 4,
					"ignorable": true
				}
			]
		}
	},
	"errors": []
}',
        ];

        yield [
            'One generic error',
            1,
            0,
            1,
            '
{
	"totals":{
		"errors":1,
		"file_errors":0
	},
	"files":[],
	"errors": [
		"first generic error"
	]
}',
        ];

        yield [
            'Multiple file errors',
            1,
            4,
            0,
            '
{
	"totals":{
		"errors":0,
		"file_errors":4
	},
	"files":{
		"/data/folder/with space/and unicode 😃/project/folder with unicode 😃/file name with \"spaces\" and unicode 😃.php":{
			"errors":2,
			"messages":[
				{
					"message": "Bar\nBar2",
					"line": 2,
					"ignorable": true
				},
				{
					"message": "Foo",
					"line": 4,
					"ignorable": true
				}
			]
		},
		"/data/folder/with space/and unicode 😃/project/foo.php":{
			"errors":2,
			"messages":[
				{
					"message": "Foo",
					"line": 1,
					"ignorable": true
				},
				{
					"message": "Bar\nBar2",
					"line": 5,
					"ignorable": true
				}
			]
		}
	},
	"errors": []
}',
        ];

        yield [
            'Multiple generic errors',
            1,
            0,
            2,
            '
{
	"totals":{
		"errors":2,
		"file_errors":0
	},
	"files":[],
	"errors": [
		"first generic error",
		"second generic error"
	]
}',
        ];

        yield [
            'Multiple file, multiple generic errors',
            1,
            4,
            2,
            '
{
	"totals":{
		"errors":2,
		"file_errors":4
	},
	"files":{
		"/data/folder/with space/and unicode 😃/project/folder with unicode 😃/file name with \"spaces\" and unicode 😃.php":{
			"errors":2,
			"messages":[
				{
					"message": "Bar\nBar2",
					"line": 2,
					"ignorable": true
				},
				{
					"message": "Foo",
					"line": 4,
					"ignorable": true
				}
			]
		},
		"/data/folder/with space/and unicode 😃/project/foo.php":{
			"errors":2,
			"messages":[
				{
					"message": "Foo",
					"line": 1,
					"ignorable": true
				},
				{
					"message": "Bar\nBar2",
					"line": 5,
					"ignorable": true
				}
			]
		}
	},
	"errors": [
		"first generic error",
		"second generic error"
	]
}',
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
    public function testPrettyFormatErrors(
        string $message,
        int $exitCode,
        int $numFileErrors,
        int $numGenericErrors,
        string $expected
    ): void {
        $formatter = new JsonErrorFormatter(true);

        $this->assertSame(
            $exitCode,
            $formatter->formatErrors(
                $this->getAnalysisResult($numFileErrors, $numGenericErrors),
                $this->getOutput()
            ),
            $message
        );

        $this->assertJsonStringEqualsJsonString($expected, $this->getOutputContent());
    }

    /**
     * @dataProvider dataFormatterOutputProvider
     *
     * @param string $message
     * @param int    $exitCode
     * @param int    $numFileErrors
     * @param int    $numGenericErrors
     * @param string $expected
     *
     */
    public function testFormatErrors(
        string $message,
        int $exitCode,
        int $numFileErrors,
        int $numGenericErrors,
        string $expected
    ): void {
        $formatter = new JsonErrorFormatter(false);

        $this->assertSame(
            $exitCode,
            $formatter->formatErrors(
                $this->getAnalysisResult($numFileErrors, $numGenericErrors),
                $this->getOutput()
            ),
            sprintf('%s: response code do not match', $message)
        );

        $this->assertJsonStringEqualsJsonString($expected, $this->getOutputContent(), sprintf('%s: JSON do not match', $message));
    }

}
