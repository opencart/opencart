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

namespace CodeLts\CliTools\Symfony;

use CodeLts\CliTools\OutputStyle;
use Symfony\Component\Console\Style\StyleInterface;

/**
 * @internal
 */
class SymfonyStyle implements OutputStyle
{

    /**
     * @var StyleInterface
     */
    private $symfonyStyle;

    public function __construct(StyleInterface $symfonyStyle)
    {
        $this->symfonyStyle = $symfonyStyle;
    }

    public function getSymfonyStyle(): StyleInterface
    {
        return $this->symfonyStyle;
    }

    public function title(string $message): void
    {
        $this->symfonyStyle->title($message);
    }

    public function section(string $message): void
    {
        $this->symfonyStyle->section($message);
    }

    public function listing(array $elements): void
    {
        $this->symfonyStyle->listing($elements);
    }

    public function success(string $message): void
    {
        $this->symfonyStyle->success($message);
    }

    public function error(string $message): void
    {
        $this->symfonyStyle->error($message);
    }

    public function warning(string $message): void
    {
        $this->symfonyStyle->warning($message);
    }

    public function note(string $message): void
    {
        $this->symfonyStyle->note($message);
    }

    public function caution(string $message): void
    {
        $this->symfonyStyle->caution($message);
    }

    public function table(array $headers, array $rows): void
    {
        $this->symfonyStyle->table($headers, $rows);
    }

    public function newLine(int $count = 1): void
    {
        $this->symfonyStyle->newLine($count);
    }

    public function progressStart(int $max = 0): void
    {
        $this->symfonyStyle->progressStart($max);
    }

    public function progressAdvance(int $step = 1): void
    {
        $this->symfonyStyle->progressAdvance($step);
    }

    public function progressFinish(): void
    {
        $this->symfonyStyle->progressFinish();
    }

}
