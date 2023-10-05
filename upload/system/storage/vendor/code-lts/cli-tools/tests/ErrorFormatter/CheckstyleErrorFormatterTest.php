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

use CodeLts\CliTools\Error;
use CodeLts\CliTools\AnalysisResult;
use CodeLts\CliTools\ErrorFormatter\CheckstyleErrorFormatter;
use CodeLts\CliTools\File\SimpleRelativePathHelper;
use CodeLts\CliTools\Tests\ErrorFormatterTestCase;

class CheckstyleErrorFormatterTest extends ErrorFormatterTestCase
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
            '<?xml version="1.0" encoding="UTF-8"?>
<checkstyle>
</checkstyle>
',
        ];

        yield [
            'One file error',
            1,
            1,
            0,
            '<?xml version="1.0" encoding="UTF-8"?>
<checkstyle>
<file name="folder with unicode 😃/file name with &quot;spaces&quot; and unicode 😃.php">
  <error line="4" column="1" severity="error" message="Foo"/>
</file>
</checkstyle>
',
        ];

        yield [
            'One generic error',
            1,
            0,
            1,
            '<?xml version="1.0" encoding="UTF-8"?>
<checkstyle>
<file>
  <error message="first generic error" severity="error"/>
</file>
</checkstyle>
',
        ];

        yield [
            'Multiple file errors',
            1,
            4,
            0,
            '<?xml version="1.0" encoding="UTF-8"?>
<checkstyle>
<file name="folder with unicode 😃/file name with &quot;spaces&quot; and unicode 😃.php">
  <error line="2" column="1" severity="error" message="Bar Bar2"/>
  <error line="4" column="1" severity="error" message="Foo"/>
</file>
<file name="foo.php">
  <error line="1" column="1" severity="error" message="Foo"/>
  <error line="5" column="1" severity="error" message="Bar Bar2"/>
</file>
</checkstyle>
',
        ];

        yield [
            'Multiple generic errors',
            1,
            0,
            2,
            '<?xml version="1.0" encoding="UTF-8"?>
<checkstyle>
<file>
  <error message="first generic error" severity="error"/>
  <error message="second generic error" severity="error"/>
</file>
</checkstyle>
',
        ];

        yield [
            'Multiple file, multiple generic errors',
            1,
            4,
            2,
            '<?xml version="1.0" encoding="UTF-8"?>
<checkstyle>
<file name="folder with unicode 😃/file name with &quot;spaces&quot; and unicode 😃.php">
  <error line="2" column="1" severity="error" message="Bar Bar2"/>
  <error line="4" column="1" severity="error" message="Foo"/>
</file>
<file name="foo.php">
  <error line="1" column="1" severity="error" message="Foo"/>
  <error line="5" column="1" severity="error" message="Bar Bar2"/>
</file>
<file>
  <error message="first generic error" severity="error"/>
  <error message="second generic error" severity="error"/>
</file>
</checkstyle>
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
        $formatter = new CheckstyleErrorFormatter(new SimpleRelativePathHelper(self::DIRECTORY_PATH));

        $this->assertSame(
            $exitCode,
            $formatter->formatErrors(
                $this->getAnalysisResult($numFileErrors, $numGenericErrors),
                $this->getOutput()
            ),
            sprintf('%s: response code do not match', $message)
        );

        $outputContent = $this->getOutputContent();
        $this->assertXmlStringEqualsXmlString($expected, $outputContent, sprintf('%s: XML do not match', $message));
        $this->assertStringStartsWith('<?xml', $outputContent);
    }

    public function testTraitPath(): void
    {
        $formatter = new CheckstyleErrorFormatter(new SimpleRelativePathHelper(__DIR__));
        $error     = new Error(
            'Foo',
            __DIR__ . '/FooTrait.php',
            5
        );
        $formatter->formatErrors(
            new AnalysisResult(
                [$error],
                [],
                [],
                []
            ),
            $this->getOutput()
        );
        $this->assertXmlStringEqualsXmlString(
            '<checkstyle>
	<file name="FooTrait.php">
		<error column="1" line="5" message="Foo" severity="error"/>
	</file>
</checkstyle>',
            $this->getOutputContent()
        );
    }

}
