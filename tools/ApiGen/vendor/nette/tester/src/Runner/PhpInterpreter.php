<?php

/**
 * This file is part of the Nette Tester.
 * Copyright (c) 2009 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Tester\Runner;

use Tester\Helpers;


/**
 * PHP command-line executable.
 */
class PhpInterpreter
{
	private string $commandLine;
	private bool $cgi;
	private \stdClass $info;
	private string $error;


	public function __construct(string $path, array $args = [])
	{
		$this->commandLine = Helpers::escapeArg($path);
		$proc = @proc_open( // @ is escalated to exception
			$this->commandLine . ' --version',
			[['pipe', 'r'], ['pipe', 'w'], ['pipe', 'w']],
			$pipes,
			null,
			null,
			['bypass_shell' => true]
		);
		if ($proc === false) {
			throw new \Exception("Cannot run PHP interpreter $path. Use -p option.");
		}

		fclose($pipes[0]);
		$output = stream_get_contents($pipes[1]);
		proc_close($proc);

		$args = ' ' . implode(' ', array_map([Helpers::class, 'escapeArg'], $args));
		if (str_contains($output, 'phpdbg')) {
			$args = ' -qrrb -S cli' . $args;
		}

		$this->commandLine .= rtrim($args);

		$proc = proc_open(
			$this->commandLine . ' -d register_argc_argv=on ' . Helpers::escapeArg(__DIR__ . '/info.php') . ' serialized',
			[['pipe', 'r'], ['pipe', 'w'], ['pipe', 'w']],
			$pipes,
			null,
			null,
			['bypass_shell' => true]
		);
		$output = stream_get_contents($pipes[1]);
		$this->error = trim(stream_get_contents($pipes[2]));
		if (proc_close($proc)) {
			throw new \Exception("Unable to run $path: " . preg_replace('#[\r\n ]+#', ' ', $this->error));
		}

		$parts = explode("\r\n\r\n", $output, 2);
		$this->cgi = count($parts) === 2;
		$this->info = @unserialize((string) strstr($parts[$this->cgi], 'O:8:"stdClass"'));
		$this->error .= strstr($parts[$this->cgi], 'O:8:"stdClass"', true);
		if (!$this->info) {
			throw new \Exception("Unable to detect PHP version (output: $output).");

		} elseif ($this->cgi && $this->error) {
			$this->error .= "\n(note that PHP CLI generates better error messages)";
		}
	}


	/**
	 * @return static
	 */
	public function withPhpIniOption(string $name, ?string $value = null): self
	{
		$me = clone $this;
		$me->commandLine .= ' -d ' . Helpers::escapeArg($name . ($value === null ? '' : "=$value"));
		return $me;
	}


	public function getCommandLine(): string
	{
		return $this->commandLine;
	}


	public function getVersion(): string
	{
		return $this->info->version;
	}


	public function getCodeCoverageEngines(): array
	{
		return $this->info->codeCoverageEngines;
	}


	public function isCgi(): bool
	{
		return $this->cgi;
	}


	public function getStartupError(): string
	{
		return $this->error;
	}


	public function getShortInfo(): string
	{
		return "PHP {$this->info->version} ({$this->info->sapi})"
			. ($this->info->phpDbgVersion ? "; PHPDBG {$this->info->phpDbgVersion}" : '');
	}


	public function hasExtension(string $name): bool
	{
		return in_array(strtolower($name), array_map('strtolower', $this->info->extensions), true);
	}
}
