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

namespace CodeLts\CliTools\File;

class FuzzyRelativePathHelper implements RelativePathHelper
{

    /**
     * @var RelativePathHelper
     */
    private $fallbackRelativePathHelper;

    /**
     * @var string
     */
    private $directorySeparator;

    /**
     * @var string|null
     */
    private $pathToTrim = null;

    /**
     * @param RelativePathHelper $fallbackRelativePathHelper
     * @param string $currentWorkingDirectory
     */
    public function __construct(
        RelativePathHelper $fallbackRelativePathHelper,
        string $currentWorkingDirectory
    ) {
        $this->fallbackRelativePathHelper = $fallbackRelativePathHelper;

        $this->directorySeparator = DIRECTORY_SEPARATOR;
        $pathBeginning            = null;
        $pathToTrimArray          = null;
        $trimBeginning            = static function (string $path): array {
            if (substr($path, 0, 1) === '/') {
                return [
                    '/',
                    substr($path, 1),
                ];
            } elseif (substr($path, 1, 1) === ':') {
                return [
                    substr($path, 0, 3),
                    substr($path, 3),
                ];
            }

            return ['', $path];
        };

        $cwdNotRootAndNotEmpty = ! in_array($currentWorkingDirectory, ['', '/'], true);
        $length3AndFoundChar   = !(strlen($currentWorkingDirectory) === 3 && substr($currentWorkingDirectory, 1, 1) === ':');

        if ($cwdNotRootAndNotEmpty && $length3AndFoundChar) {
            [$pathBeginning, $currentWorkingDirectory] = $trimBeginning($currentWorkingDirectory);

            /** @var string[] $pathToTrimArray */
            $pathToTrimArray = explode($this->directorySeparator, $currentWorkingDirectory);
        }

        if ($pathToTrimArray === null || count($pathToTrimArray) === 0) {
            return;
        }

        $pathToTrim     = $pathBeginning . implode($this->directorySeparator, $pathToTrimArray);
        $realPathToTrim = realpath($pathToTrim);
        if ($realPathToTrim !== false) {
            $pathToTrim = $realPathToTrim;
        }

        $this->pathToTrim = $pathToTrim;
    }

    public function getRelativePath(string $filename): string
    {
        if ($this->pathToTrim !== null && strpos($filename, $this->pathToTrim) === 0) {
            return ltrim(substr($filename, strlen($this->pathToTrim)), $this->directorySeparator);
        }

        return $this->fallbackRelativePathHelper->getRelativePath($filename);
    }

}
