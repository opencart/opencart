<?php

declare(strict_types = 1);

/*
 * (c) Copyright (c) 2016-2022 Ondřej Mirtes <ondrej@mirtes.cz>
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

/**
 * Allow errors to be reported in pull-requests diff when run in a GitHub Action
 * @see https://help.github.com/en/actions/reference/workflow-commands-for-github-actions#setting-an-error-message
 */
class GithubErrorFormatter implements ErrorFormatter
{

    /**
     * @var RelativePathHelper
     */
    private $relativePathHelper;

    /**
     * @var ErrorFormatter
     */
    private $errorFormatter;

    public function __construct(
        RelativePathHelper $relativePathHelper,
        ErrorFormatter $errorFormatter
    ) {
        $this->relativePathHelper = $relativePathHelper;
        $this->errorFormatter     = $errorFormatter;
    }

    public function formatErrors(AnalysisResult $analysisResult, Output $output): int
    {
        $this->errorFormatter->formatErrors($analysisResult, $output);

        foreach ($analysisResult->getFileSpecificErrors() as $fileSpecificError) {
            $metas = [
                'file' => $fileSpecificError->getFile() !== null ? $this->relativePathHelper->getRelativePath($fileSpecificError->getFile()) : '?',
                'line' => $fileSpecificError->getLine(),
                'col' => 0,
            ];
            array_walk(
                $metas,
                static function (&$value, string $key): void {
                    $value = sprintf('%s=%s', $key, (string) $value);
                }
            );

            $message = $fileSpecificError->getMessage();
            // newlines need to be encoded
            // see https://github.com/actions/starter-workflows/issues/68#issuecomment-581479448
            $message = str_replace("\n", '%0A', $message);

            $line = sprintf('::error %s::%s', implode(',', $metas), $message);

            $output->writeRaw($line);
            $output->writeLineFormatted('');
        }

        foreach ($analysisResult->getNotFileSpecificErrors() as $notFileSpecificError) {
            // newlines need to be encoded
            // see https://github.com/actions/starter-workflows/issues/68#issuecomment-581479448
            $notFileSpecificError = str_replace("\n", '%0A', $notFileSpecificError);

            $line = sprintf('::error ::%s', $notFileSpecificError);

            $output->writeRaw($line);
            $output->writeLineFormatted('');
        }

        foreach ($analysisResult->getWarnings() as $warning) {
            // newlines need to be encoded
            // see https://github.com/actions/starter-workflows/issues/68#issuecomment-581479448
            $warning = str_replace("\n", '%0A', $warning);

            $line = sprintf('::warning ::%s', $warning);

            $output->writeRaw($line);
            $output->writeLineFormatted('');
        }

        return $analysisResult->hasErrors() ? 1 : 0;
    }

}
