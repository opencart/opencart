<?php

/**
 * This file is part of the Nette Tester.
 * Copyright (c) 2009 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Tester\Runner;

use Tester\CodeCoverage;
use Tester\Dumper;
use Tester\Environment;
use Tester\Helpers;


/**
 * CLI Tester.
 */
class CliTester
{
	private array $options;
	private PhpInterpreter $interpreter;
	private bool $debugMode = true;
	private ?string $stdoutFormat = null;


	public function run(): ?int
	{
		Environment::setupColors();
		$this->setupErrors();

		ob_start();
		$cmd = $this->loadOptions();

		$this->debugMode = (bool) $this->options['--debug'];
		if (isset($this->options['--colors'])) {
			Environment::$useColors = (bool) $this->options['--colors'];
		} elseif (in_array($this->stdoutFormat, ['tap', 'junit'], true)) {
			Environment::$useColors = false;
		}

		if ($cmd->isEmpty() || $this->options['--help']) {
			$cmd->help();
			return null;
		}

		$this->createPhpInterpreter();

		if ($this->options['--info']) {
			$job = new Job(new Test(__DIR__ . '/info.php'), $this->interpreter);
			$job->setTempDirectory($this->options['--temp']);
			$job->run();
			echo $job->getTest()->stdout;
			return null;
		}

		$runner = $this->createRunner();
		$runner->setEnvironmentVariable(Environment::VariableRunner, '1');
		$runner->setEnvironmentVariable(Environment::VariableColors, (string) (int) Environment::$useColors);

		$this->installInterruptHandler();

		if ($this->options['--coverage']) {
			$coverageFile = $this->prepareCodeCoverage($runner);
		}

		if ($this->stdoutFormat !== null) {
			ob_clean();
		}

		ob_end_flush();

		if ($this->options['--watch']) {
			$this->watch($runner);
			return null;
		}

		$result = $runner->run();

		if (isset($coverageFile) && preg_match('#\.(?:html?|xml)$#D', $coverageFile)) {
			$this->finishCodeCoverage($coverageFile);
		}

		return $result ? 0 : 1;
	}


	private function loadOptions(): CommandLine
	{
		$outputFiles = [];

		echo <<<'XX'
			 _____ ___  ___ _____ ___  ___
			|_   _/ __)( __/_   _/ __)| _ )
			  |_| \___ /___) |_| \___ |_|_\  v2.5.0


			XX;

		$cmd = new CommandLine(
			<<<'XX'
				Usage:
				    tester [options] [<test file> | <directory>]...

				Options:
				    -p <path>                    Specify PHP interpreter to run (default: php).
				    -c <path>                    Look for php.ini file (or look in directory) <path>.
				    -C                           Use system-wide php.ini.
				    -d <key=value>...            Define INI entry 'key' with value 'value'.
				    -s                           Show information about skipped tests.
				    --stop-on-fail               Stop execution upon the first failure.
				    -j <num>                     Run <num> jobs in parallel (default: 8).
				    -o <console|tap|junit|log|none>  (e.g. -o junit:output.xml)
				                                 Specify one or more output formats with optional file name.
				    -w | --watch <path>          Watch directory.
				    -i | --info                  Show tests environment info and exit.
				    --setup <path>               Script for runner setup.
				    --temp <path>                Path to temporary directory. Default by sys_get_temp_dir().
				    --colors [1|0]               Enable or disable colors.
				    --coverage <path>            Generate code coverage report to file.
				    --coverage-src <path>        Path to source code.
				    -h | --help                  This help.

				XX,
			[
				'-c' => [CommandLine::RealPath => true],
				'--watch' => [CommandLine::Repeatable => true, CommandLine::RealPath => true],
				'--setup' => [CommandLine::RealPath => true],
				'--temp' => [],
				'paths' => [CommandLine::Repeatable => true, CommandLine::Value => getcwd()],
				'--debug' => [],
				'--cider' => [],
				'--coverage-src' => [CommandLine::RealPath => true, CommandLine::Repeatable => true],
				'-o' => [CommandLine::Repeatable => true, CommandLine::Normalizer => function ($arg) use (&$outputFiles) {
					[$format, $file] = explode(':', $arg, 2) + [1 => null];

					if (isset($outputFiles[$file])) {
						throw new \Exception(
							$file === null
								? 'Option -o <format> without file name parameter can be used only once.'
								: "Cannot specify output by -o into file '$file' more then once."
						);
					} elseif ($file === null) {
						$this->stdoutFormat = $format;
					}

					$outputFiles[$file] = true;

					return [$format, $file];
				}],
			]
		);

		if (isset($_SERVER['argv'])) {
			if (($tmp = array_search('-l', $_SERVER['argv'], true))
				|| ($tmp = array_search('-log', $_SERVER['argv'], true))
				|| ($tmp = array_search('--log', $_SERVER['argv'], true))
			) {
				$_SERVER['argv'][$tmp] = '-o';
				$_SERVER['argv'][$tmp + 1] = 'log:' . $_SERVER['argv'][$tmp + 1];
			}

			if ($tmp = array_search('--tap', $_SERVER['argv'], true)) {
				unset($_SERVER['argv'][$tmp]);
				$_SERVER['argv'] = array_merge($_SERVER['argv'], ['-o', 'tap']);
			}
		}

		$this->options = $cmd->parse();
		if ($this->options['--temp'] === null) {
			if (($temp = sys_get_temp_dir()) === '') {
				echo "Note: System temporary directory is not set.\n";
			} elseif (($real = realpath($temp)) === false) {
				echo "Note: System temporary directory '$temp' does not exist.\n";
			} else {
				$this->options['--temp'] = Helpers::prepareTempDir($real);
			}
		} else {
			$this->options['--temp'] = Helpers::prepareTempDir($this->options['--temp']);
		}

		return $cmd;
	}


	private function createPhpInterpreter(): void
	{
		$args = $this->options['-C'] ? [] : ['-n'];
		if ($this->options['-c']) {
			array_push($args, '-c', $this->options['-c']);
		} elseif (!$this->options['--info'] && !$this->options['-C']) {
			echo "Note: No php.ini is used.\n";
		}

		if (in_array($this->stdoutFormat, ['tap', 'junit'], true)) {
			array_push($args, '-d', 'html_errors=off');
		}

		foreach ($this->options['-d'] as $item) {
			array_push($args, '-d', $item);
		}

		$this->interpreter = new PhpInterpreter($this->options['-p'], $args);

		if ($error = $this->interpreter->getStartupError()) {
			echo Dumper::color('red', "PHP startup error: $error") . "\n";
		}
	}


	private function createRunner(): Runner
	{
		$runner = new Runner($this->interpreter);
		$runner->paths = $this->options['paths'];
		$runner->threadCount = max(1, (int) $this->options['-j']);
		$runner->stopOnFail = (bool) $this->options['--stop-on-fail'];
		$runner->setTempDirectory($this->options['--temp']);

		if ($this->stdoutFormat === null) {
			$runner->outputHandlers[] = new Output\ConsolePrinter(
				$runner,
				(bool) $this->options['-s'],
				'php://output',
				(bool) $this->options['--cider']
			);
		}

		foreach ($this->options['-o'] as $output) {
			[$format, $file] = $output;
			match ($format) {
				'console' => $runner->outputHandlers[] = new Output\ConsolePrinter($runner, (bool) $this->options['-s'], $file, (bool) $this->options['--cider']),
				'tap' => $runner->outputHandlers[] = new Output\TapPrinter($file),
				'junit' => $runner->outputHandlers[] = new Output\JUnitPrinter($file),
				'log' => $runner->outputHandlers[] = new Output\Logger($runner, $file),
				'none' => null,
				default => throw new \LogicException("Undefined output printer '$format'.'"),
			};
		}

		if ($this->options['--setup']) {
			(function () use ($runner): void {
				require func_get_arg(0);
			})($this->options['--setup']);
		}

		return $runner;
	}


	private function prepareCodeCoverage(Runner $runner): string
	{
		$engines = $this->interpreter->getCodeCoverageEngines();
		if (count($engines) < 1) {
			throw new \Exception("Code coverage functionality requires Xdebug or PCOV extension or PHPDBG SAPI (used {$this->interpreter->getCommandLine()})");
		}

		file_put_contents($this->options['--coverage'], '');
		$file = realpath($this->options['--coverage']);

		[$engine, $version] = reset($engines);

		$runner->setEnvironmentVariable(Environment::VariableCoverage, $file);
		$runner->setEnvironmentVariable(Environment::VariableCoverageEngine, $engine);

		if ($engine === CodeCoverage\Collector::EngineXdebug && version_compare($version, '3.0.0', '>=')) {
			$runner->addPhpIniOption('xdebug.mode', ltrim(ini_get('xdebug.mode') . ',coverage', ','));
		}

		if ($engine === CodeCoverage\Collector::EnginePcov && count($this->options['--coverage-src'])) {
			$runner->addPhpIniOption('pcov.directory', Helpers::findCommonDirectory($this->options['--coverage-src']));
		}

		echo "Code coverage by $engine: $file\n";
		return $file;
	}


	private function finishCodeCoverage(string $file): void
	{
		if (!in_array($this->stdoutFormat, ['none', 'tap', 'junit'], true)) {
			echo 'Generating code coverage report... ';
		}

		if (filesize($file) === 0) {
			echo 'failed. Coverage file is empty. Do you call Tester\Environment::setup() in tests?' . "\n";
			return;
		}

		$generator = pathinfo($file, PATHINFO_EXTENSION) === 'xml'
			? new CodeCoverage\Generators\CloverXMLGenerator($file, $this->options['--coverage-src'])
			: new CodeCoverage\Generators\HtmlGenerator($file, $this->options['--coverage-src']);
		$generator->render($file);
		echo round($generator->getCoveredPercent()) . "% covered\n";
	}


	private function watch(Runner $runner): void
	{
		$prev = [];
		$counter = 0;
		while (true) {
			$state = [];
			foreach ($this->options['--watch'] as $directory) {
				foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($directory)) as $file) {
					if (substr($file->getExtension(), 0, 3) === 'php' && substr($file->getBasename(), 0, 1) !== '.') {
						$state[(string) $file] = @filemtime((string) $file); // @ file could be deleted in the meantime
					}
				}
			}

			if ($state !== $prev) {
				$prev = $state;
				try {
					$runner->run();
				} catch (\ErrorException $e) {
					$this->displayException($e);
				}

				echo "\n";
				$time = time();
			}

			$idle = time() - $time;
			if ($idle >= 60 * 60) {
				$idle = 'long time';
			} elseif ($idle >= 60) {
				$idle = round($idle / 60) . ' min';
			} else {
				$idle .= ' sec';
			}

			echo 'Watching ' . implode(', ', $this->options['--watch']) . " (idle for $idle) " . str_repeat('.', ++$counter % 5) . "    \r";
			sleep(2);
		}
	}


	private function setupErrors(): void
	{
		error_reporting(E_ALL);
		ini_set('html_errors', '0');

		set_error_handler(function (int $severity, string $message, string $file, int $line) {
			if (($severity & error_reporting()) === $severity) {
				throw new \ErrorException($message, 0, $severity, $file, $line);
			}

			return false;
		});

		set_exception_handler(function (\Throwable $e) {
			if (!$e instanceof InterruptException) {
				$this->displayException($e);
			}

			exit(2);
		});
	}


	private function displayException(\Throwable $e): void
	{
		echo "\n";
		echo $this->debugMode
			? Dumper::dumpException($e)
			: Dumper::color('white/red', 'Error: ' . $e->getMessage());
		echo "\n";
	}


	private function installInterruptHandler(): void
	{
		if (function_exists('pcntl_signal')) {
			pcntl_signal(SIGINT, function (): void {
				pcntl_signal(SIGINT, SIG_DFL);
				throw new InterruptException;
			});
			pcntl_async_signals(true);

		} elseif (function_exists('sapi_windows_set_ctrl_handler') && PHP_SAPI === 'cli') {
			sapi_windows_set_ctrl_handler(function (): void {
				throw new InterruptException;
			});
		}
	}
}
