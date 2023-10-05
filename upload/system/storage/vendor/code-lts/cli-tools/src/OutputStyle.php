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

interface OutputStyle
{

    public function title(string $message): void;

    public function section(string $message): void;

    /**
     * @param string[] $elements
     */
    public function listing(array $elements): void;

    public function success(string $message): void;

    public function error(string $message): void;

    public function warning(string $message): void;

    public function note(string $message): void;

    public function caution(string $message): void;

    /**
     * @param mixed[] $headers
     * @param mixed[] $rows
     */
    public function table(array $headers, array $rows): void;

    public function newLine(int $count = 1): void;

    public function progressStart(int $max = 0): void;

    public function progressAdvance(int $step = 1): void;

    public function progressFinish(): void;

}
