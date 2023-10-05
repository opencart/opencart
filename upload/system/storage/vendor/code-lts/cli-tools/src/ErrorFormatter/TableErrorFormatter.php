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

class TableErrorFormatter implements ErrorFormatter
{

    /**
     * @var RelativePathHelper
     */
    private $relativePathHelper;

    public function __construct(
        RelativePathHelper $relativePathHelper
    ) {
        $this->relativePathHelper = $relativePathHelper;
    }

    public function formatErrors(
        AnalysisResult $analysisResult,
        Output $output
    ): int {
        $style = $output->getStyle();

        if (!$analysisResult->hasErrors() && !$analysisResult->hasWarnings()) {
            $style->success('No errors');

            return 0;
        }

        /** @var array<string, \CodeLts\CliTools\Error[]> $fileErrors */
        $fileErrors = [];
        foreach ($analysisResult->getFileSpecificErrors() as $fileSpecificError) {
            if (!isset($fileErrors[$fileSpecificError->getFile()])) {
                $fileErrors[$fileSpecificError->getFile()] = [];
            }

            $fileErrors[$fileSpecificError->getFile()][] = $fileSpecificError;
        }

        foreach ($fileErrors as $file => $errors) {
            $rows = [];
            foreach ($errors as $error) {
                $message = $error->getMessage();
                if ($error->getTip() !== null) {
                    $tip      = $error->getTip();
                    $message .= "\n💡 " . $tip;
                }
                $rows[] = [
                    (string) $error->getLine(),
                    $message,
                ];
            }

            $relativeFilePath = $this->relativePathHelper->getRelativePath($file);

            $style->table(['Line', $relativeFilePath], $rows);
        }

        if (count($analysisResult->getNotFileSpecificErrors()) > 0) {
            $style->table(
                ['', 'Error'],
                array_map(
                    static function (string $error): array {
                        return ['', $error];
                    },
                    $analysisResult->getNotFileSpecificErrors()
                )
            );
        }

        $warningsCount = count($analysisResult->getWarnings());
        if ($warningsCount > 0) {
            $style->table(
                ['', 'Warning'],
                array_map(
                    static function (string $warning): array {
                        return ['', $warning];
                    },
                    $analysisResult->getWarnings()
                )
            );
        }

        $finalMessage = sprintf($analysisResult->getTotalErrorsCount() === 1 ? 'Found %d error' : 'Found %d errors', $analysisResult->getTotalErrorsCount());
        if ($warningsCount > 0) {
            $finalMessage .= sprintf($warningsCount === 1 ? ' and %d warning' : ' and %d warnings', $warningsCount);
        }

        if ($analysisResult->getTotalErrorsCount() > 0) {
            $style->error($finalMessage);
        } else {
            $style->warning($finalMessage);
        }

        return $analysisResult->getTotalErrorsCount() > 0 ? 1 : 0;
    }

}
