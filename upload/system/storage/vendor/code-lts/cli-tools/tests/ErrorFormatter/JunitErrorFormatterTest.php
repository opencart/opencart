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

use CodeLts\CliTools\ErrorFormatter\JunitErrorFormatter;
use DOMDocument;
use Generator;
use CodeLts\CliTools\File\SimpleRelativePathHelper;
use CodeLts\CliTools\Tests\ErrorFormatterTestCase;

class JunitErrorFormatterTest extends ErrorFormatterTestCase
{

    /** @var \CodeLts\CliTools\ErrorFormatter\JunitErrorFormatter */
    private $formatter;

    public function setUp(): void
    {
        parent::setUp();

        $this->formatter = new JunitErrorFormatter(new SimpleRelativePathHelper(self::DIRECTORY_PATH));
    }

    /**
     * @return \Generator<array<int, string|int>>
     */
    public function dataFormatterOutputProvider(): Generator
    {
        yield 'No errors' => [
            0,
            0,
            0,
            '<?xml version="1.0" encoding="UTF-8"?>
<testsuite failures="0" name="cli-tools" tests="0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="https://raw.githubusercontent.com/junit-team/junit5/r5.5.1/platform-tests/src/test/resources/jenkins-junit.xsd">
  <testcase name="cli-tools"/>
</testsuite>
',
        ];

        yield 'One file error' => [
            1,
            1,
            0,
            '<?xml version="1.0" encoding="UTF-8"?>
<testsuite failures="1" name="cli-tools" tests="1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="https://raw.githubusercontent.com/junit-team/junit5/r5.5.1/platform-tests/src/test/resources/jenkins-junit.xsd">
  <testcase name="folder with unicode &#x1F603;/file name with &quot;spaces&quot; and unicode &#x1F603;.php:4">
    <failure type="ERROR" message="Foo" />
  </testcase>
</testsuite>
',
        ];

        yield 'One generic error' => [
            1,
            0,
            1,
            '<?xml version="1.0" encoding="UTF-8"?>
<testsuite failures="1" name="cli-tools" tests="1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="https://raw.githubusercontent.com/junit-team/junit5/r5.5.1/platform-tests/src/test/resources/jenkins-junit.xsd">
  <testcase name="General error">
    <failure type="ERROR" message="first generic error" />
  </testcase>
</testsuite>
',
        ];

        yield 'Multiple file errors' => [
            1,
            4,
            0,
            '<?xml version="1.0" encoding="UTF-8"?>
<testsuite failures="4" name="cli-tools" tests="4" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="https://raw.githubusercontent.com/junit-team/junit5/r5.5.1/platform-tests/src/test/resources/jenkins-junit.xsd">
  <testcase name="folder with unicode &#x1F603;/file name with &quot;spaces&quot; and unicode &#x1F603;.php:2">
    <failure type="ERROR" message="Bar Bar2" />
  </testcase>
  <testcase name="folder with unicode &#x1F603;/file name with &quot;spaces&quot; and unicode &#x1F603;.php:4">
    <failure type="ERROR" message="Foo" />
  </testcase>
  <testcase name="foo.php:1">
    <failure type="ERROR" message="Foo"/>
  </testcase>
  <testcase name="foo.php:5">
    <failure type="ERROR" message="Bar Bar2"/>
  </testcase>
</testsuite>
',
        ];

        yield 'Multiple generic errors' => [
            1,
            0,
            2,
            '<?xml version="1.0" encoding="UTF-8"?>
<testsuite failures="2" name="cli-tools" tests="2" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="https://raw.githubusercontent.com/junit-team/junit5/r5.5.1/platform-tests/src/test/resources/jenkins-junit.xsd">
  <testcase name="General error">
    <failure type="ERROR" message="first generic error" />
  </testcase>
  <testcase name="General error">
    <failure type="ERROR" message="second generic error"/>
  </testcase>
</testsuite>
',
        ];

        yield 'Multiple file, multiple generic errors' => [
            1,
            4,
            2,
            '<?xml version="1.0" encoding="UTF-8"?>
<testsuite failures="6" name="cli-tools" tests="6" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="https://raw.githubusercontent.com/junit-team/junit5/r5.5.1/platform-tests/src/test/resources/jenkins-junit.xsd">
  <testcase name="folder with unicode &#x1F603;/file name with &quot;spaces&quot; and unicode &#x1F603;.php:2">
    <failure type="ERROR" message="Bar Bar2" />
  </testcase>
  <testcase name="folder with unicode &#x1F603;/file name with &quot;spaces&quot; and unicode &#x1F603;.php:4">
    <failure type="ERROR" message="Foo" />
  </testcase>
  <testcase name="foo.php:1">
    <failure type="ERROR" message="Foo"/>
  </testcase>
  <testcase name="foo.php:5">
    <failure type="ERROR" message="Bar Bar2"/>
  </testcase>
  <testcase name="General error">
    <failure type="ERROR" message="first generic error" />
  </testcase>
  <testcase name="General error">
    <failure type="ERROR" message="second generic error"/>
  </testcase>
</testsuite>
',
        ];
    }

    /**
     * Test generated use cases for JUnit output format.
     *
     * @dataProvider dataFormatterOutputProvider
     */
    public function testFormatErrors(
        int $exitCode,
        int $numFileErrors,
        int $numGeneralErrors,
        string $expected
    ): void {
        $this->assertSame(
            $exitCode,
            $this->formatter->formatErrors(
                $this->getAnalysisResult($numFileErrors, $numGeneralErrors),
                $this->getOutput()
            ),
            'Response code do not match'
        );

        $xml = new DOMDocument();
        $xml->loadXML($this->getOutputContent());

        $this->assertTrue(
            $xml->schemaValidate(__DIR__ . '/junit-schema.xsd'),
            'Schema do not validate'
        );

        $this->assertXmlStringEqualsXmlString(
            $expected,
            $this->getOutputContent(),
            'XML do not match'
        );
    }

}
