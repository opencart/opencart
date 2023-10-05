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

namespace CodeLts\CliTools;

class AnalysisResult
{

    /** @var \CodeLts\CliTools\Error[] sorted by their file name, line number and message */
    private $fileSpecificErrors;

    /** @var string[] */
    private $notFileSpecificErrors;

    /** @var string[] */
    private $internalErrors;

    /** @var string[] */
    private $warnings;

    /**
     * @param \CodeLts\CliTools\Error[] $fileSpecificErrors
     * @param string[] $notFileSpecificErrors
     * @param string[] $internalErrors
     * @param string[] $warnings
     */
    public function __construct(
        array $fileSpecificErrors,
        array $notFileSpecificErrors,
        array $internalErrors,
        array $warnings
    ) {
        usort(
            $fileSpecificErrors,
            static function (Error $a, Error $b): int {
                return [
                    $a->getFile(),
                    $a->getLine(),
                    $a->getMessage(),
                ] <=> [
                    $b->getFile(),
                    $b->getLine(),
                    $b->getMessage(),
                ];
            }
        );

        $this->fileSpecificErrors    = $fileSpecificErrors;
        $this->notFileSpecificErrors = $notFileSpecificErrors;
        $this->internalErrors        = $internalErrors;
        $this->warnings              = $warnings;
    }

    public function hasErrors(): bool
    {
        return $this->getTotalErrorsCount() > 0;
    }

    public function getTotalErrorsCount(): int
    {
        return count($this->fileSpecificErrors) + count($this->notFileSpecificErrors);
    }

    /**
     * @return \CodeLts\CliTools\Error[] sorted by their file name, line number and message
     */
    public function getFileSpecificErrors(): array
    {
        return $this->fileSpecificErrors;
    }

    /**
     * @return string[]
     */
    public function getNotFileSpecificErrors(): array
    {
        return $this->notFileSpecificErrors;
    }

    /**
     * @return string[]
     */
    public function getInternalErrors(): array
    {
        return $this->internalErrors;
    }

    /**
     * @return string[]
     */
    public function getWarnings(): array
    {
        return $this->warnings;
    }

    public function hasWarnings(): bool
    {
        return count($this->warnings) > 0;
    }

    public function hasInternalErrors(): bool
    {
        return count($this->internalErrors) > 0;
    }

}
