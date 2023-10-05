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

/**
 * @see https://www.jetbrains.com/help/teamcity/build-script-interaction-with-teamcity.html#Reporting+Inspections
 */
class TeamcityErrorFormatter implements ErrorFormatter
{

    /**
     * @var RelativePathHelper
     */
    private $relativePathHelper;

    public function __construct(RelativePathHelper $relativePathHelper)
    {
        $this->relativePathHelper = $relativePathHelper;
    }

    public function formatErrors(AnalysisResult $analysisResult, Output $output): int
    {
        $result                = '';
        $fileSpecificErrors    = $analysisResult->getFileSpecificErrors();
        $notFileSpecificErrors = $analysisResult->getNotFileSpecificErrors();
        $warnings              = $analysisResult->getWarnings();

        if (count($fileSpecificErrors) === 0 && count($notFileSpecificErrors) === 0 && count($warnings) === 0) {
            return 0;
        }

        $result .= $this->createTeamcityLine(
            'inspectionType',
            [
            'id' => 'cli-tools',
            'name' => 'cli-tools',
            'category' => 'cli-tools',
            'description' => 'cli-tools Errors',
            ]
        );

        foreach ($fileSpecificErrors as $fileSpecificError) {
            $result .= $this->createTeamcityLine(
                'inspection',
                [
                'typeId' => 'cli-tools',
                'message' => $fileSpecificError->getMessage(),
                'file' => $fileSpecificError->getFile() !== null ? $this->relativePathHelper->getRelativePath($fileSpecificError->getFile()) : '?',
                'line' => $fileSpecificError->getLine(),
                // additional attributes
                'SEVERITY' => 'ERROR',
                'ignorable' => $fileSpecificError->canBeIgnored(),
                'tip' => $fileSpecificError->getTip(),
                ]
            );
        }

        foreach ($notFileSpecificErrors as $notFileSpecificError) {
            $result .= $this->createTeamcityLine(
                'inspection',
                [
                'typeId' => 'cli-tools',
                'message' => $notFileSpecificError,
                // the file is required
                'file' => '.',
                'SEVERITY' => 'ERROR',
                ]
            );
        }

        foreach ($warnings as $warning) {
            $result .= $this->createTeamcityLine(
                'inspection',
                [
                'typeId' => 'cli-tools',
                'message' => $warning,
                // the file is required
                'file' => '.',
                'SEVERITY' => 'WARNING',
                ]
            );
        }

        $output->writeRaw($result);

        return $analysisResult->hasErrors() ? 1 : 0;
    }

    /**
     * Creates a Teamcity report line
     *
     * @param string $messageName The message name
     * @param mixed[] $keyValuePairs The key=>value pairs
     * @return string The Teamcity report line
     */
    private function createTeamcityLine(string $messageName, array $keyValuePairs): string
    {
        $string = '##teamcity[' . $messageName;
        foreach ($keyValuePairs as $key => $value) {
            if (is_string($value)) {
                $value = $this->escape($value);
            }
            $string .= ' ' . $key . '=\'' . $value . '\'';
        }
        return $string . ']' . PHP_EOL;
    }

    /**
     * Escapes the given string for Teamcity output
     *
     * @param string $string The string to escape
     * @return string The escaped string
     */
    private function escape(string $string): string
    {
        $replacements = [
            '~\n~' => '|n',
            '~\r~' => '|r',
            '~([\'\|\[\]])~' => '|$1',
        ];
        return (string) preg_replace(array_keys($replacements), array_values($replacements), $string);
    }

}
