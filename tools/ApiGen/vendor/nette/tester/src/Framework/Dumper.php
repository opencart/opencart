<?php

/**
 * This file is part of the Nette Tester.
 * Copyright (c) 2009 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Tester;


/**
 * Dumps PHP variables.
 * @internal
 */
class Dumper
{
	public static int $maxLength = 70;
	public static int $maxDepth = 10;
	public static string $dumpDir = 'output';
	public static int $maxPathSegments = 3;
	public static $pathSeparator;


	/**
	 * Dumps information about a variable in readable format.
	 */
	public static function toLine(mixed $var): string
	{
		if (is_bool($var)) {
			return $var ? 'true' : 'false';

		} elseif ($var === null) {
			return 'null';

		} elseif (is_int($var)) {
			return "$var";

		} elseif (is_float($var)) {
			return var_export($var, true);

		} elseif (is_string($var)) {
			if (preg_match('#^(.{' . self::$maxLength . '}).#su', $var, $m)) {
				$var = "$m[1]...";
			} elseif (strlen($var) > self::$maxLength) {
				$var = substr($var, 0, self::$maxLength) . '...';
			}

			return self::encodeStringLine($var);

		} elseif (is_array($var)) {
			$out = '';
			$counter = 0;
			foreach ($var as $k => &$v) {
				$out .= ($out === '' ? '' : ', ');
				if (strlen($out) > self::$maxLength) {
					$out .= '...';
					break;
				}

				$out .= ($k === $counter ? '' : self::toLine($k) . ' => ')
					. (is_array($v) && $v ? '[...]' : self::toLine($v));
				$counter = is_int($k) ? max($k + 1, $counter) : $counter;
			}

			return "[$out]";

		} elseif ($var instanceof \Throwable) {
			return 'Exception ' . $var::class . ': ' . ($var->getCode() ? '#' . $var->getCode() . ' ' : '') . $var->getMessage();

		} elseif ($var instanceof Expect) {
			return $var->dump();

		} elseif (is_object($var)) {
			return self::objectToLine($var);

		} elseif (is_resource($var)) {
			return 'resource(' . get_resource_type($var) . ')';

		} else {
			return 'unknown type';
		}
	}


	/**
	 * Formats object to line.
	 */
	private static function objectToLine(object $object): string
	{
		$line = $object::class;
		if ($object instanceof \DateTime || $object instanceof \DateTimeInterface) {
			$line .= '(' . $object->format('Y-m-d H:i:s O') . ')';
		}

		return $line . '(' . self::hash($object) . ')';
	}


	/**
	 * Dumps variable in PHP format.
	 */
	public static function toPhp(mixed $var): string
	{
		return self::_toPhp($var);
	}


	/**
	 * Returns object's stripped hash.
	 */
	private static function hash(object $object): string
	{
		return '#' . substr(md5(spl_object_hash($object)), 0, 4);
	}


	private static function _toPhp(mixed &$var, array &$list = [], int $level = 0, int &$line = 1): string
	{
		if (is_float($var)) {
			$var = str_replace(',', '.', "$var");
			return !str_contains($var, '.') ? $var . '.0' : $var;

		} elseif (is_bool($var)) {
			return $var ? 'true' : 'false';

		} elseif ($var === null) {
			return 'null';

		} elseif (is_string($var)) {
			$res = self::encodeStringPhp($var);
			$line += substr_count($res, "\n");
			return $res;

		} elseif (is_array($var)) {
			$space = str_repeat("\t", $level);

			static $marker;
			if ($marker === null) {
				$marker = uniqid("\x00", true);
			}

			if (empty($var)) {
				$out = '';

			} elseif ($level > self::$maxDepth || isset($var[$marker])) {
				return '/* Nesting level too deep or recursive dependency */';

			} else {
				$out = "\n$space";
				$outShort = '';
				$var[$marker] = true;
				$oldLine = $line;
				$line++;
				$counter = 0;
				foreach ($var as $k => &$v) {
					if ($k !== $marker) {
						$item = ($k === $counter ? '' : self::_toPhp($k, $list, $level + 1, $line) . ' => ') . self::_toPhp($v, $list, $level + 1, $line);
						$counter = is_int($k) ? max($k + 1, $counter) : $counter;
						$outShort .= ($outShort === '' ? '' : ', ') . $item;
						$out .= "\t$item,\n$space";
						$line++;
					}
				}

				unset($var[$marker]);
				if (!str_contains($outShort, "\n") && strlen($outShort) < self::$maxLength) {
					$line = $oldLine;
					$out = $outShort;
				}
			}

			return '[' . $out . ']';

		} elseif ($var instanceof \Closure) {
			$rc = new \ReflectionFunction($var);
			return "/* Closure defined in file {$rc->getFileName()} on line {$rc->getStartLine()} */";

		} elseif (is_object($var)) {
			if (($rc = new \ReflectionObject($var))->isAnonymous()) {
				return "/* Anonymous class defined in file {$rc->getFileName()} on line {$rc->getStartLine()} */";
			}

			$arr = (array) $var;
			$space = str_repeat("\t", $level);
			$class = $var::class;
			$used = &$list[spl_object_hash($var)];

			if (empty($arr)) {
				$out = '';

			} elseif ($used) {
				return "/* $class dumped on line $used */";

			} elseif ($level > self::$maxDepth) {
				return '/* Nesting level too deep */';

			} else {
				$out = "\n";
				$used = $line;
				$line++;
				foreach ($arr as $k => &$v) {
					if (isset($k[0]) && $k[0] === "\x00") {
						$k = substr($k, strrpos($k, "\x00") + 1);
					}

					$out .= "$space\t" . self::_toPhp($k, $list, $level + 1, $line) . ' => ' . self::_toPhp($v, $list, $level + 1, $line) . ",\n";
					$line++;
				}

				$out .= $space;
			}

			$hash = self::hash($var);
			return $class === 'stdClass'
				? "(object) /* $hash */ [$out]"
				: "$class::__set_state(/* $hash */ [$out])";

		} elseif (is_resource($var)) {
			return '/* resource ' . get_resource_type($var) . ' */';

		} else {
			return var_export($var, true);
		}
	}


	private static function encodeStringPhp(string $s): string
	{
		$special = [
			"\r" => '\r',
			"\n" => '\n',
			"\t" => "\t",
			"\e" => '\e',
			'\\' => '\\\\',
		];
		$utf8 = preg_match('##u', $s);
		$escaped = preg_replace_callback(
			$utf8 ? '#[\p{C}\\\\]#u' : '#[\x00-\x1F\x7F-\xFF\\\\]#',
			function ($m) use ($special) {
				return $special[$m[0]] ?? (strlen($m[0]) === 1
						? '\x' . str_pad(strtoupper(dechex(ord($m[0]))), 2, '0', STR_PAD_LEFT) . ''
						: '\u{' . strtoupper(ltrim(dechex(self::utf8Ord($m[0])), '0')) . '}');
			},
			$s
		);
		return $s === str_replace('\\\\', '\\', $escaped)
			? "'" . preg_replace('#\'|\\\\(?=[\'\\\\]|$)#D', '\\\\$0', $s) . "'"
			: '"' . addcslashes($escaped, '"$') . '"';
	}


	private static function encodeStringLine(string $s): string
	{
		$special = [
			"\r" => "\\r\r",
			"\n" => "\\n\n",
			"\t" => "\\t\t",
			"\e" => '\\e',
			"'" => "'",
		];
		$utf8 = preg_match('##u', $s);
		$escaped = preg_replace_callback(
			$utf8 ? '#[\p{C}\']#u' : '#[\x00-\x1F\x7F-\xFF\']#',
			function ($m) use ($special) {
				return "\e[22m"
					. ($special[$m[0]] ?? (strlen($m[0]) === 1
						? '\x' . str_pad(strtoupper(dechex(ord($m[0]))), 2, '0', STR_PAD_LEFT)
						: '\u{' . strtoupper(ltrim(dechex(self::utf8Ord($m[0])), '0')) . '}'))
					. "\e[1m";
			},
			$s
		);
		return "'" . $escaped . "'";
	}


	private static function utf8Ord(string $c): int
	{
		$ord0 = ord($c[0]);
		if ($ord0 < 0x80) {
			return $ord0;
		} elseif ($ord0 < 0xE0) {
			return ($ord0 << 6) + ord($c[1]) - 0x3080;
		} elseif ($ord0 < 0xF0) {
			return ($ord0 << 12) + (ord($c[1]) << 6) + ord($c[2]) - 0xE2080;
		} else {
			return ($ord0 << 18) + (ord($c[1]) << 12) + (ord($c[2]) << 6) + ord($c[3]) - 0x3C82080;
		}
	}


	public static function dumpException(\Throwable $e): string
	{
		$trace = $e->getTrace();
		array_splice($trace, 0, $e instanceof \ErrorException ? 1 : 0, [['file' => $e->getFile(), 'line' => $e->getLine()]]);

		$testFile = null;
		foreach (array_reverse($trace) as $item) {
			if (isset($item['file'])) { // in case of shutdown handler, we want to skip inner-code blocks and debugging calls
				$testFile = $item['file'];
				break;
			}
		}

		if ($e instanceof AssertException) {
			$expected = $e->expected;
			$actual = $e->actual;
			$testFile = $e->outputName
				? dirname($testFile) . '/' . $e->outputName . '.foo'
				: $testFile;

			if (is_object($expected) || is_array($expected) || (is_string($expected) && strlen($expected) > self::$maxLength)
				|| is_object($actual) || is_array($actual) || (is_string($actual) && (strlen($actual) > self::$maxLength || preg_match('#[\x00-\x1F]#', $actual)))
			) {
				$args = isset($_SERVER['argv'][1])
					? '.[' . implode(' ', preg_replace(['#^-*([^|]+).*#i', '#[^=a-z0-9. -]+#i'], ['$1', '-'], array_slice($_SERVER['argv'], 1))) . ']'
					: '';
				$stored[] = self::saveOutput($testFile, $expected, $args . '.expected');
				$stored[] = self::saveOutput($testFile, $actual, $args . '.actual');
			}

			if ((is_string($actual) && is_string($expected))) {
				for ($i = 0; $i < strlen($actual) && isset($expected[$i]) && $actual[$i] === $expected[$i]; $i++);
				for (; $i && $i < strlen($actual) && $actual[$i - 1] >= "\x80" && $actual[$i] >= "\x80" && $actual[$i] < "\xC0"; $i--);
				$i = max(0, min(
					$i - (int) (self::$maxLength / 3), // try to display 1/3 of shorter string
					max(strlen($actual), strlen($expected)) - self::$maxLength + 3 // 3 = length of ...
				));
				if ($i) {
					$expected = substr_replace($expected, '...', 0, $i);
					$actual = substr_replace($actual, '...', 0, $i);
				}
			}

			$message = 'Failed: ' . $e->origMessage;
			if (((is_string($actual) && is_string($expected)) || (is_array($actual) && is_array($expected)))
				&& preg_match('#^(.*)(%\d)(.*)(%\d.*)$#Ds', $message, $m)
			) {
				$message = ($delta = strlen($m[1]) - strlen($m[3])) >= 3
					? "$m[1]$m[2]\n" . str_repeat(' ', $delta - 3) . "...$m[3]$m[4]"
					: "$m[1]$m[2]$m[3]\n" . str_repeat(' ', strlen($m[1]) - 4) . "... $m[4]";
			}

			$message = strtr($message, [
				'%1' => self::color('yellow') . self::toLine($actual) . self::color('white'),
				'%2' => self::color('yellow') . self::toLine($expected) . self::color('white'),
			]);
		} else {
			$message = ($e instanceof \ErrorException ? Helpers::errorTypeToString($e->getSeverity()) : $e::class)
				. ': ' . preg_replace('#[\x00-\x09\x0B-\x1F]+#', ' ', $e->getMessage());
		}

		$s = self::color('white', $message) . "\n\n"
			. (isset($stored) ? 'diff ' . Helpers::escapeArg($stored[0]) . ' ' . Helpers::escapeArg($stored[1]) . "\n\n" : '');

		foreach ($trace as $item) {
			$item += ['file' => null, 'class' => null, 'type' => null, 'function' => null];
			if ($e instanceof AssertException && $item['file'] === __DIR__ . DIRECTORY_SEPARATOR . 'Assert.php') {
				continue;
			}

			$line = $item['class'] === Assert::class && method_exists($item['class'], $item['function'])
				&& strpos($tmp = file($item['file'])[$item['line'] - 1], "::$item[function](") ? $tmp : null;

			$s .= 'in '
				. ($item['file']
					? (
						($item['file'] === $testFile ? self::color('white') : '')
						. implode(
							self::$pathSeparator ?? DIRECTORY_SEPARATOR,
							array_slice(explode(DIRECTORY_SEPARATOR, $item['file']), -self::$maxPathSegments)
						)
						. "($item[line])" . self::color('gray') . ' '
					)
					: '[internal function]'
				)
				. ($line
					? trim($line)
					: $item['class'] . $item['type'] . $item['function'] . ($item['function'] ? '()' : '')
				)
				. self::color() . "\n";
		}

		if ($e->getPrevious()) {
			$s .= "\n(previous) " . static::dumpException($e->getPrevious());
		}

		return $s;
	}


	/**
	 * Dumps data to folder 'output'.
	 */
	public static function saveOutput(string $testFile, mixed $content, string $suffix = ''): string
	{
		$path = self::$dumpDir . DIRECTORY_SEPARATOR . pathinfo($testFile, PATHINFO_FILENAME) . $suffix;
		if (!preg_match('#/|\w:#A', self::$dumpDir)) {
			$path = dirname($testFile) . DIRECTORY_SEPARATOR . $path;
		}

		@mkdir(dirname($path)); // @ - directory may already exist
		file_put_contents($path, is_string($content) ? $content : (self::toPhp($content) . "\n"));
		return $path;
	}


	/**
	 * Applies color to string.
	 */
	public static function color(string $color = '', ?string $s = null): string
	{
		$colors = [
			'black' => '0;30', 'gray' => '1;30', 'silver' => '0;37', 'white' => '1;37',
			'navy' => '0;34', 'blue' => '1;34', 'green' => '0;32', 'lime' => '1;32',
			'teal' => '0;36', 'aqua' => '1;36', 'maroon' => '0;31', 'red' => '1;31',
			'purple' => '0;35', 'fuchsia' => '1;35', 'olive' => '0;33', 'yellow' => '1;33',
			null => '0',
		];
		$c = explode('/', $color);
		return "\e["
			. str_replace(';', "m\e[", $colors[$c[0]] . (empty($c[1]) ? '' : ';4' . substr($colors[$c[1]], -1)))
			. 'm' . $s . ($s === null ? '' : "\e[0m");
	}


	public static function removeColors(string $s): string
	{
		return preg_replace('#\e\[[\d;]+m#', '', $s);
	}
}
