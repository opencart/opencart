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

namespace CodeLts\CliTools\ErrorFormatter;

use CodeLts\CliTools\AnalysisResult;
use CodeLts\CliTools\Output;
use CodeLts\CliTools\File\RelativePathHelper;

use function sprintf;

class JunitErrorFormatter implements ErrorFormatter
{

    /**
     * @var RelativePathHelper
     */
    private $relativePathHelper;

    public function __construct(RelativePathHelper $relativePathHelper)
    {
        $this->relativePathHelper = $relativePathHelper;
    }

    public function formatErrors(
        AnalysisResult $analysisResult,
        Output $output
    ): int {
        $result  = '<?xml version="1.0" encoding="UTF-8"?>';
        $result .= sprintf(
            '<testsuite failures="%d" name="cli-tools" tests="%d" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="https://raw.githubusercontent.com/junit-team/junit5/r5.5.1/platform-tests/src/test/resources/jenkins-junit.xsd">',
            $analysisResult->getTotalErrorsCount(),
            $analysisResult->getTotalErrorsCount()
        );

        foreach ($analysisResult->getFileSpecificErrors() as $fileSpecificError) {
            $file = $fileSpecificError->getFile();
            if ($file === null) {
                continue;
            }

            $fileName = $this->relativePathHelper->getRelativePath($file);
            $result  .= $this->createTestCase(
                sprintf('%s:%s', $fileName, (string) $fileSpecificError->getLine()),
                'ERROR',
                $this->escape($fileSpecificError->getMessage())
            );
        }

        foreach ($analysisResult->getNotFileSpecificErrors() as $notFileSpecificError) {
            $result .= $this->createTestCase('General error', 'ERROR', $this->escape($notFileSpecificError));
        }

        foreach ($analysisResult->getWarnings() as $warning) {
            $result .= $this->createTestCase('Warning', 'WARNING', $this->escape($warning));
        }

        if (!$analysisResult->hasErrors()) {
            $result .= $this->createTestCase('cli-tools', '');
        }

        $result .= '</testsuite>';

        $output->writeRaw($result);

        return $analysisResult->hasErrors() ? 1 : 0;
    }

    /**
     * Format a single test case
     *
     * @param string      $reference
     * @param string|null $message
     *
     * @return string
     */
    private function createTestCase(string $reference, string $type, ?string $message = null): string
    {
        $result = sprintf('<testcase name="%s">', $this->escape($reference));

        if ($message !== null) {
            $result .= sprintf('<failure type="%s" message="%s" />', $this->escape($type), $this->escape($message));
        }

        $result .= '</testcase>';

        return $result;
    }

    /**
     * Escapes values for using in XML
     *
     * @param string $string
     * @return string
     */
    protected function escape(string $string): string
    {
        return htmlspecialchars($string, ENT_XML1 | ENT_COMPAT, 'UTF-8');
    }

}
