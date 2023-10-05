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

class CheckstyleErrorFormatter implements ErrorFormatter
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
        $output->writeRaw('<?xml version="1.0" encoding="UTF-8"?>');
        $output->writeLineFormatted('');
        $output->writeRaw('<checkstyle>');
        $output->writeLineFormatted('');

        foreach ($this->groupByFile($analysisResult) as $relativeFilePath => $errors) {
            $output->writeRaw(
                sprintf(
                    '<file name="%s">',
                    $this->escape($relativeFilePath)
                )
            );
            $output->writeLineFormatted('');

            foreach ($errors as $error) {
                $output->writeRaw(
                    sprintf(
                        '  <error line="%d" column="1" severity="error" message="%s" />',
                        $this->escape((string) $error->getLine()),
                        $this->escape((string) $error->getMessage())
                    )
                );
                $output->writeLineFormatted('');
            }
            $output->writeRaw('</file>');
            $output->writeLineFormatted('');
        }

        $notFileSpecificErrors = $analysisResult->getNotFileSpecificErrors();

        if (count($notFileSpecificErrors) > 0) {
            $output->writeRaw('<file>');
            $output->writeLineFormatted('');

            foreach ($notFileSpecificErrors as $error) {
                $output->writeRaw(sprintf('  <error severity="error" message="%s" />', $this->escape($error)));
                $output->writeLineFormatted('');
            }

            $output->writeRaw('</file>');
            $output->writeLineFormatted('');
        }

        if ($analysisResult->hasWarnings()) {
            $output->writeRaw('<file>');
            $output->writeLineFormatted('');

            foreach ($analysisResult->getWarnings() as $warning) {
                $output->writeRaw(sprintf('  <error severity="warning" message="%s" />', $this->escape($warning)));
                $output->writeLineFormatted('');
            }

            $output->writeRaw('</file>');
            $output->writeLineFormatted('');
        }

        $output->writeRaw('</checkstyle>');
        $output->writeLineFormatted('');

        return $analysisResult->hasErrors() ? 1 : 0;
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

    /**
     * Group errors by file
     *
     * @param AnalysisResult $analysisResult
     * @return array<string, array> Array that have as key the relative path of file
     *                              and as value an array with occurred errors.
     */
    private function groupByFile(AnalysisResult $analysisResult): array
    {
        $files = [];

        /** @var \CodeLts\CliTools\Error $fileSpecificError */
        foreach ($analysisResult->getFileSpecificErrors() as $fileSpecificError) {
            $absolutePath = $fileSpecificError->getFile();
            if ($absolutePath === null) {
                continue;
            }
            $relativeFilePath = $this->relativePathHelper->getRelativePath(
                $absolutePath
            );

            $files[$relativeFilePath][] = $fileSpecificError;
        }

        return $files;
    }

}
