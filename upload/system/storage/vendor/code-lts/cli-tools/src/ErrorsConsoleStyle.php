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

use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ErrorsConsoleStyle extends \Symfony\Component\Console\Style\SymfonyStyle
{

    public const OPTION_NO_PROGRESS = 'no-progress';

    /** @var bool */
    private $showProgress;

    /** @var ProgressBar */
    private $progressBar;

    /** @var bool|null */
    private $isCiDetected = null;

    public function __construct(InputInterface $input, OutputInterface $output)
    {
        parent::__construct($input, $output);
        $this->showProgress = $input->hasOption(self::OPTION_NO_PROGRESS) && !(bool) $input->getOption(self::OPTION_NO_PROGRESS);
    }

    private function isCiDetected(): bool
    {
        if ($this->isCiDetected === null) {
            $this->isCiDetected = Utils::isCiDetected();
        }

        return $this->isCiDetected;
    }

    /**
     * @param string[] $headers
     * @param string[][] $rows
     */
    public function table(array $headers, array $rows): void
    {
        /** @var int $terminalWidth */
        $terminalWidth  = (new \Symfony\Component\Console\Terminal())->getWidth() - 2;
        $maxHeaderWidth = strlen($headers[0]);
        foreach ($rows as $row) {
            $length = strlen($row[0]);
            if ($maxHeaderWidth !== 0 && $length <= $maxHeaderWidth) {
                continue;
            }

            $maxHeaderWidth = $length;
        }

        $wrap = static function ($rows) use ($terminalWidth, $maxHeaderWidth) {
            return array_map(
                static function ($row) use ($terminalWidth, $maxHeaderWidth) {
                    return array_map(
                        static function ($s) use ($terminalWidth, $maxHeaderWidth) {
                            if ($terminalWidth > $maxHeaderWidth + 5) {
                                return wordwrap(
                                    $s,
                                    $terminalWidth - $maxHeaderWidth - 5,
                                    "\n",
                                    true
                                );
                            }

                            return $s;
                        },
                        $row
                    );
                },
                $rows
            );
        };

        parent::table($headers, $wrap($rows));
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
     * @param int $max
     */
    public function createProgressBar($max = 0): ProgressBar
    {
        $this->progressBar = parent::createProgressBar($max);
        $this->progressBar->setOverwrite(!$this->isCiDetected());
        return $this->progressBar;
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
     * @param int $max
     */
    public function progressStart($max = 0): void
    {
        if (!$this->showProgress) {
            return;
        }
        parent::progressStart($max);
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
     * @param int $step
     */
    public function progressAdvance($step = 1): void
    {
        if (!$this->showProgress) {
            return;
        }

        if (!$this->isCiDetected() && $step > 0) {
            $stepTime = (time() - $this->progressBar->getStartTime()) / $step;
            if ($stepTime > 0 && $stepTime < 1) {
                $this->progressBar->setRedrawFrequency((int) (1 / $stepTime));
            } else {
                $this->progressBar->setRedrawFrequency(1);
            }
        }

        parent::progressAdvance($step);
    }

    public function progressFinish(): void
    {
        if (!$this->showProgress) {
            return;
        }
        parent::progressFinish();
    }

}
