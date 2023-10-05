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

use CodeLts\CliTools\Output;
use CodeLts\CliTools\OutputStyle;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @internal
 */
class SymfonyOutput implements Output
{

    /**
     * @var OutputInterface
     */
    private $symfonyOutput;

    /**
     * @var OutputStyle
     */
    private $style;

    public function __construct(
        OutputInterface $symfonyOutput,
        OutputStyle $style
    ) {
        $this->symfonyOutput = $symfonyOutput;
        $this->style         = $style;
    }

    public function writeFormatted(string $message): void
    {
        $this->symfonyOutput->write($message, false, OutputInterface::OUTPUT_NORMAL);
    }

    public function writeLineFormatted(string $message): void
    {
        $this->symfonyOutput->writeln($message, OutputInterface::OUTPUT_NORMAL);
    }

    public function writeRaw(string $message): void
    {
        $this->symfonyOutput->write($message, false, OutputInterface::OUTPUT_RAW);
    }

    public function getStyle(): OutputStyle
    {
        return $this->style;
    }

    public function isVerbose(): bool
    {
        return $this->symfonyOutput->isVerbose();
    }

    public function isDebug(): bool
    {
        return $this->symfonyOutput->isDebug();
    }

    public function isDecorated(): bool
    {
        return $this->symfonyOutput->isDecorated();
    }

}
