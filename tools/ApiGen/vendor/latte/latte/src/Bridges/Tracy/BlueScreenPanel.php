<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Bridges\Tracy;

use Latte;
use Tracy;
use Tracy\BlueScreen;
use Tracy\Helpers;


/**
 * BlueScreen panels for Tracy 2.x
 */
class BlueScreenPanel
{
	private static bool $initialized = false;


	public static function initialize(?BlueScreen $blueScreen = null): void
	{
		if (self::$initialized) {
			return;
		}
		self::$initialized = true;

		$blueScreen ??= Tracy\Debugger::getBlueScreen();
		$blueScreen->addPanel([self::class, 'renderError']);
		$blueScreen->addAction([self::class, 'renderUnknownMacro']);
		if (
			version_compare(Tracy\Debugger::VERSION, '2.9.0', '>=')
			&& version_compare(Tracy\Debugger::VERSION, '3.0', '<')
		) {
			Tracy\Debugger::addSourceMapper([self::class, 'mapLatteSourceCode']);
			$blueScreen->addFileGenerator(fn(string $file) => substr($file, -6) === '.latte'
					? "{block content}\n\$END\$"
					: null);
		}
	}


	public static function renderError(?\Throwable $e): ?array
	{
		if ($e instanceof Latte\CompileException && $e->sourceName) {
			return [
				'tab' => 'Template',
				'panel' => (preg_match('#\n|\?#', $e->sourceName)
						? ''
						: '<p>'
							. (@is_file($e->sourceName) // @ - may trigger error
								? '<b>File:</b> ' . Helpers::editorLink($e->sourceName, $e->position?->line)
								: '<b>' . htmlspecialchars($e->sourceName . ($e->position?->line ? ':' . $e->position->line : '')) . '</b>')
							. '</p>')
					. '<pre class="code tracy-code"><div>'
					. BlueScreen::highlightLine(htmlspecialchars($e->sourceCode, ENT_IGNORE, 'UTF-8'), $e->position->line ?? 0, 15, $e->position->column ?? 0)
					. '</div></pre>',
			];

		} elseif (
			$e
			&& ($file = $e->getFile())
			&& (version_compare(Tracy\Debugger::VERSION, '2.9.0', '<'))
			&& ($mapped = self::mapLatteSourceCode($file, $e->getLine()))
		) {
			return [
				'tab' => 'Template',
				'panel' => '<p><b>File:</b> ' . Helpers::editorLink($mapped['file'], $mapped['line']) . '</p>'
					. ($mapped['line']
						? BlueScreen::highlightFile($mapped['file'], $mapped['line'])
						: ''),
			];
		}

		return null;
	}


	public static function renderUnknownMacro(?\Throwable $e): ?array
	{
		if (
			$e instanceof Latte\CompileException
			&& $e->sourceName
			&& @is_file($e->sourceName) // @ - may trigger error
			&& (preg_match('#Unknown tag (\{\w+)\}, did you mean (\{\w+)\}\?#A', $e->getMessage(), $m)
				|| preg_match('#Unknown attribute (n:\w+), did you mean (n:\w+)\?#A', $e->getMessage(), $m))
		) {
			return [
				'link' => Helpers::editorUri($e->sourceName, $e->position?->line, 'fix', $m[1], $m[2]),
				'label' => 'fix it',
			];
		}

		return null;
	}


	/** @return array{file: string, line: int, label: string, active: bool} */
	public static function mapLatteSourceCode(string $file, int $line): ?array
	{
		if (!strpos($file, '.latte--')) {
			return null;
		}

		$lines = file($file);
		if (
			!preg_match('#^/\*\* source: (\S+\.latte)#m', implode('', array_slice($lines, 0, 10)), $m)
			|| !@is_file($m[1]) // @ - may trigger error
		) {
			return null;
		}

		$file = $m[1];
		$line = $line && preg_match('#/\* line (\d+) \*/#', $lines[$line - 1], $m) ? (int) $m[1] : 0;
		return ['file' => $file, 'line' => $line, 'label' => 'Latte', 'active' => true];
	}
}
