<?php

declare(strict_types = 1);

/*
 * This file is part of the Doctum utility.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Doctum\Console\Command;

use CodeLts\CliTools\AnalysisResult;
use CodeLts\CliTools\AnsiEscapeSequences;
use CodeLts\CliTools\ErrorsConsoleStyle;
use CodeLts\CliTools\Exceptions\FormatNotFoundException;
use CodeLts\CliTools\OutputFormat;
use CodeLts\CliTools\Symfony\SymfonyOutput;
use CodeLts\CliTools\Symfony\SymfonyStyle;
use Doctum\Message;
use Doctum\Parser\Transaction;
use Doctum\Project;
use Doctum\Renderer\Diff;
use Doctum\Doctum;
use Doctum\Version\Version;
use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

abstract class Command extends BaseCommand
{
    private const PARSE_ERROR = 64;

    /**
     * @var Doctum
     */
    protected $doctum;

    /**
     * @var string|Version
     */
    protected $version;

    /**
     * @var bool
     */
    protected $started;

    /**
     * @var bool
     */
    protected $progressStarted = false;

    /**
     * @var array<string,Diff>
     */
    protected $diffs = [];

    /**
     * @var array<string,Transaction>
     */
    protected $transactions = [];

    /**
     * @var \Doctum\Parser\ParseError[]
     */
    protected $errors = [];

    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var SymfonyOutput
     */
    protected $output;

    /**
     * @var SymfonyOutput
     */
    protected $errorOutput;

    /**
     * @var string|null
     */
    protected $sourceRootDirectory = null;

    /**
     * @see Command
     * @phpstan-return void
     */
    protected function configure()
    {
        $this->getDefinition()->addArgument(new InputArgument('config', InputArgument::REQUIRED, 'The configuration file'));
        $this->getDefinition()->addOption(new InputOption('only-version', '', InputOption::VALUE_REQUIRED, 'The version to build'));
    }

    protected function addForceOption(): void
    {
        $this->getDefinition()->addOption(new InputOption('force', '', InputOption::VALUE_NONE, 'Forces to rebuild from scratch', null));
    }

    protected function addOutputFormatOption(): void
    {
        $this->getDefinition()->addOption(
            new InputOption(
                'output-format',
                '',
                InputOption::VALUE_REQUIRED,
                'The format to display errors',
                OutputFormat::OUTPUT_FORMAT_RAW_TEXT
            )
        );
    }

    protected function addNoProgressOption(): void
    {
        $this->getDefinition()->addOption(new InputOption('no-progress', '', InputOption::VALUE_NONE, 'Do not display the progress bar', null));
    }

    protected function addIgnoreParseErrors(): void
    {
        $this->getDefinition()->addOption(
            new InputOption('ignore-parse-errors', '', InputOption::VALUE_NONE, 'Ignores parse errors and exits 0', null)
        );
    }

    protected function addPrintFrozenErrors(): void
    {
        $this->getDefinition()->addOption(
            new InputOption('print-frozen-errors', '', InputOption::VALUE_NONE, 'Enables printing errors for frozen versions', null)
        );
    }

    /**
     * @phpstan-return void
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $stdErr      = $output;

        if ($output instanceof ConsoleOutputInterface) {
            $stdErr = $output->getErrorOutput();
        }
        $errorConsoleStyle = new ErrorsConsoleStyle($this->input, $output);
        $this->output      = new SymfonyOutput($output, new SymfonyStyle($errorConsoleStyle));
        $this->errorOutput = new SymfonyOutput($stdErr, new SymfonyStyle($errorConsoleStyle));

        /** @var string|null $config */
        $config     = $input->getArgument('config');
        $filesystem = new Filesystem();

        if ($config && !$filesystem->isAbsolutePath($config)) {
            $config = getcwd() . '/' . $config;
        }

        if ($config === null || !is_file($config)) {
            throw new \InvalidArgumentException(sprintf('Configuration file "%s" does not exist.', $config));
        }

        $this->doctum = $this->loadDoctum($config);

        if (!$this->doctum instanceof Doctum) {
            throw new \RuntimeException(sprintf('Configuration file "%s" must return a Doctum instance.', $config));
        }

        if ($input->getOption('only-version')) {
            /** @var string $onlyVersionOption */
            $onlyVersionOption = $input->getOption('only-version');
            $this->doctum->setVersion((string) $onlyVersionOption);
        }
    }

    public function update(Project $project): int
    {
        if (! $this->checkOptionsValues()) {
            return 1;
        }

        $this->sourceRootDirectory = $project->getSourceDir();
        $this->output->writeFormatted(
            $this->output->isDecorated() ? '<bg=cyan;fg=white> Updating project </>' : 'Updating project'
        );
        $project->update([$this, 'messageCallback'], $this->input->getOption('force'));

        $this->displayParseSummary();
        $this->displayRenderSummary();

        return $this->getExitCode();
    }

    public function parse(Project $project): int
    {
        if (! $this->checkOptionsValues()) {
            return 1;
        }

        $project->parse([$this, 'messageCallback'], $this->input->getOption('force'));
        $this->sourceRootDirectory = $project->getSourceDir();
        $this->output->writeFormatted(
            $this->output->isDecorated() ? '<bg=cyan;fg=white> Parsing project </>' : 'Parsing project'
        );

        $this->displayParseSummary();

        return $this->getExitCode();
    }

    public function render(Project $project): int
    {
        if (! $this->checkOptionsValues()) {
            return 1;
        }

        $project->render([$this, 'messageCallback'], $this->input->getOption('force'));
        $this->sourceRootDirectory = $project->getSourceDir();
        $this->output->writeFormatted(
            $this->output->isDecorated() ? '<bg=cyan;fg=white> Rendering project </>' : 'Rendering project'
        );

        $this->displayRenderSummary();

        return $this->getExitCode();
    }

    private function checkOptionsValues(): bool
    {
        try {
            OutputFormat::checkOutputFormatIsValid($this->getOutputFormat());
            return true;
        } catch (FormatNotFoundException $e) {
            $this->output->getStyle()->error($e->getMessage());
            return false;
        }
    }

    private function getOutputFormat(): string
    {
        /** @var string $outputFormat */
        $outputFormat = $this->input->getOption('output-format');
        return (string) $outputFormat;
    }

    private function getExitCode(): int
    {
        if ($this->input->getOption('ignore-parse-errors')) {
            return 0;
        }
        if (count($this->errors) > 0) {
            return self::PARSE_ERROR;
        }
        return 0;
    }

    /**
     * @param mixed $data
     */
    public function messageCallback(int $message, $data): void
    {
        switch ($message) {
            case Message::PARSE_CLASS:
                [$step, $steps, $class] = $data;
                $this->displayParseProgress($class);
                $this->makeProgress($step, $steps);
                break;
            case Message::PARSE_ERROR:
                $this->errors = array_merge($this->errors, $data);
                break;
            case Message::SWITCH_VERSION:
                $this->version = $data;
                $this->errors  = [];
                $this->started = false;
                $this->displayNewVersion();
                break;
            case Message::PARSE_VERSION_FINISHED:
                $this->transactions[(string) $this->version] = $data;
                $this->displayParseEnd($data);
                $this->endProgress();
                $this->started = false;
                break;
            case Message::RENDER_VERSION_FINISHED:
                $this->diffs[(string) $this->version] = $data;
                $this->displayRenderEnd($data);
                $this->endProgress();
                $this->started = false;
                break;
            case Message::RENDER_PROGRESS:
                [$section, $message, $step, $steps] = $data;
                $this->displayRenderProgress($section, $message);
                $this->makeProgress($step, $steps);
                break;
        }
    }

    protected function makeProgress(int $step, int $steps): void
    {
        if ($this->progressStarted === false) {
            $this->output->getStyle()->progressStart($steps);
            $this->progressStarted = true;
        }
        $this->output->getStyle()->progressAdvance(1);
    }

    protected function endProgress(): void
    {
        if ($this->progressStarted === false) {
            return;
        }
        $this->progressStarted = false;
        $this->output->getStyle()->progressFinish();
    }

    /**
     * @param \Doctum\Reflection\ClassReflection $class
     */
    public function displayParseProgress($class): void
    {
        if (! $this->started) {
            $this->started = true;
        }

        if ($this->progressStarted === false) {
            // This avoids to have a "Parsing" stuck before the "Parsing" in progress
            $this->output->writeRaw("\n");
            return;
        }

        if ($this->output->isDecorated()) {
            $this->output->writeRaw(AnsiEscapeSequences::MOVE_CURSOR_UP_2);
        }

        $errorsPluralText = (1 === count($this->errors) ? '' : 's');
        $this->output->writeFormatted(
            $this->output->isDecorated() ? sprintf(
                '  Parsing %s' . AnsiEscapeSequences::ERASE_TO_LINE_END . "\n          %s"
                . AnsiEscapeSequences::ERASE_TO_LINE_END . "\n",
                count($this->errors) ? ' <fg=red>' . count($this->errors) . ' error' . $errorsPluralText . '</>' : '',
                $class->getName()
            ) : sprintf(
                'Parsing %s %s' . "\n",
                $class->getName(),
                count($this->errors) ? 'total: ' . count($this->errors) . ' error' . $errorsPluralText : ''
            )
        );
    }

    public function displayRenderProgress(string $section, string $message): void
    {
        if (! $this->started) {
            $this->started = true;
        }

        if ($this->progressStarted === false) {
            // This avoids to have a "Rendering" stuck before the "Rendering" in progress
            $this->output->writeRaw("\n");
            return;
        }

        if ($this->output->isDecorated()) {
            $this->output->writeRaw(AnsiEscapeSequences::MOVE_CURSOR_UP_2);
        }

        $this->output->writeFormatted(
            $this->output->isDecorated() ? sprintf(
                '  Rendering '
                . AnsiEscapeSequences::ERASE_TO_LINE_END
                . "\n            <info>%s</info> %s" . AnsiEscapeSequences::ERASE_TO_LINE_END . "\n",
                $section,
                $message
            ) : sprintf(
                'Rendering %s %s' . "\n",
                $section,
                $message
            )
        );
    }

    public function displayParseEnd(Transaction $transaction): void
    {
        if (!$this->started) {
            return;
        }

        $this->output->writeFormatted(
            $this->output->isDecorated() ? AnsiEscapeSequences::MOVE_CURSOR_UP_2 . '<info>  Parsing   done</info>'
            . AnsiEscapeSequences::ERASE_TO_LINE_END . "\n"
            . AnsiEscapeSequences::ERASE_TO_LINE_END . "\n" . AnsiEscapeSequences::MOVE_CURSOR_UP_1 : 'Parsing done' . "\n"
        );

        $isFrozenVersion = $this->version instanceof Version
                            && $this->version->isFrozen()
                            && ! $this->input->getOption('print-frozen-errors');

        // Do not display errors for frozen versions, it makes no sense (except if the user explicitly wants it)
        if ($this->output->isVerbose() && count($this->errors) > 0 && $isFrozenVersion === false) {
            $this->output->writeLineFormatted('');
            $analysisResult = new AnalysisResult(
                $this->errors,
                [],
                [],
                []
            );

            OutputFormat::displayUserChoiceFormat(
                $this->getOutputFormat(),
                $analysisResult,
                $this->sourceRootDirectory,
                $this->errorOutput
            );
            $this->output->writeLineFormatted('');
        }
    }

    public function displayRenderEnd(Diff $diff): void
    {
        if (!$this->started) {
            return;
        }

        $this->output->writeFormatted(
            $this->output->isDecorated() ? AnsiEscapeSequences::MOVE_CURSOR_UP_2
            . '<info>  Rendering done</info>'
            . AnsiEscapeSequences::ERASE_TO_LINE_END . "\n"
            . AnsiEscapeSequences::ERASE_TO_LINE_END . "\n"
            . AnsiEscapeSequences::MOVE_CURSOR_UP_1 : 'Rendering done' . "\n"
        );
        $this->output->writeLineFormatted('');
    }

    public function displayParseSummary(): void
    {
        if (count($this->transactions) <= 0) {
            return;
        }

        $this->output->writeLineFormatted('');// Display a line break after the title
        $this->output->writeLineFormatted(
            '<bg=cyan;fg=white> Version </>  <bg=cyan;fg=white> Updated C </>  <bg=cyan;fg=white> Removed C </>'
        );

        foreach ($this->transactions as $version => $transaction) {
            $this->output->writeLineFormatted(
                sprintf(
                    '%9s  %11d  %11d',
                    $version,
                    count($transaction->getModifiedClasses()),
                    count($transaction->getRemovedClasses())
                )
            );
        }
        $this->output->writeLineFormatted('');
    }

    public function displayRenderSummary(): void
    {
        if (count($this->diffs) <= 0) {
            return;
        }

        $this->output->writeLineFormatted('');// Display a line break after the title
        $this->output->writeLineFormatted(
            '<bg=cyan;fg=white> Version </>  <bg=cyan;fg=white> Updated C </>'
            . '  <bg=cyan;fg=white> Updated N </>  <bg=cyan;fg=white> Removed C </>'
            . '  <bg=cyan;fg=white> Removed N </>'
        );

        foreach ($this->diffs as $version => $diff) {
            $this->output->writeLineFormatted(
                sprintf(
                    '%9s  %11d  %11d  %11d  %11d',
                    $version,
                    count($diff->getModifiedClasses()),
                    count($diff->getModifiedNamespaces()),
                    count($diff->getRemovedClasses()),
                    count($diff->getRemovedNamespaces())
                )
            );
        }
        $this->output->writeLineFormatted('');
    }

    public function displayNewVersion(): void
    {
        $this->output->getStyle()->section(sprintf("\n<fg=cyan>Version %s</>", $this->version));
    }

    /**
     * @return Doctum
     */
    private function loadDoctum(string $config)
    {
        return require $config;
    }

}
