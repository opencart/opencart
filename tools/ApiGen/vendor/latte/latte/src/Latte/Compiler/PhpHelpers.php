<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte\CompileException;


/**
 * PHP helpers.
 * @internal
 */
final class PhpHelpers
{
	/**
	 * Optimizes code readability.
	 */
	public static function reformatCode(string $source): string
	{
		$res = '';
		$lastChar = ';';
		$tokens = new \ArrayIterator(token_get_all($source));
		$level = 0;

		foreach ($tokens as $n => $token) {
			$next = $tokens[$n + 1] ?? [null, ''];

			if (is_array($token)) {
				[$name, $token] = $token;
				if ($name === T_ELSE || $name === T_ELSEIF) {
					if ($next === ':' && $lastChar === '}') {
						$res .= ';'; // semicolon needed in if(): ... if() ... else:
					}

					$lastChar = '';
					$res .= $token;

				} elseif ($name === T_DOC_COMMENT || $name === T_COMMENT) {
					$res .= preg_replace("#\n[ \t]*+(?!\n)#", "\n" . str_repeat("\t", $level), $token);

				} elseif ($name === T_WHITESPACE) {
					$prev = $tokens[$n - 1];
					$lines = substr_count($token, "\n");
					if ($prev === '}' && in_array($next[0], [T_ELSE, T_ELSEIF, T_CATCH, T_FINALLY], true)) {
						$token = ' ';
					} elseif ($prev === '{' || $prev === '}' || $prev === ';' || $lines) {
						$token = str_repeat("\n", max(1, $lines)) . str_repeat("\t", $level); // indent last line
					} elseif ($prev[0] === T_OPEN_TAG) {
						$token = '';
					}

					$res .= $token;

				} elseif ($name === T_OBJECT_OPERATOR) {
					$lastChar = '->';
					$res .= $token;

				} elseif ($name === T_OPEN_TAG) {
					$res .= "<?php\n";

				} elseif ($name === T_CLOSE_TAG) {
					throw new \LogicException('Unexpected token');

				} else {
					if (in_array($name, [T_CURLY_OPEN, T_DOLLAR_OPEN_CURLY_BRACES], true)) {
						$level++;
					}

					$lastChar = '';
					$res .= $token;
				}
			} else {
				if ($token === '{' || $token === '[') {
					$level++;
				} elseif ($token === '}' || $token === ']') {
					$level--;
					$res .= "\x08";

				} elseif ($token === ';') {
					if ($next[0] !== T_WHITESPACE) {
						$token .= "\n" . str_repeat("\t", $level); // indent last line
					}
				}

				$lastChar = $token;
				$res .= $token;
			}
		}

		$res = str_replace(["\t\x08", "\x08"], '', $res);
		return $res;
	}


	public static function dump(mixed $value, bool $multiline = false): string
	{
		if (is_array($value)) {
			$indexed = $value && array_keys($value) === range(0, count($value) - 1);
			$s = '';
			foreach ($value as $k => $v) {
				$s .= $multiline
					? ($s === '' ? "\n" : '') . "\t" . ($indexed ? '' : self::dump($k) . ' => ') . self::dump($v) . ",\n"
					: ($s === '' ? '' : ', ') . ($indexed ? '' : self::dump($k) . ' => ') . self::dump($v);
			}

			return '[' . $s . ']';
		} elseif ($value === null) {
			return 'null';
		} else {
			return var_export($value, true);
		}
	}


	public static function optimizeEcho(string $source): string
	{
		$res = '';
		$tokens = token_get_all($source);
		$start = null;

		for ($i = 0; $i < \count($tokens); $i++) {
			$token = $tokens[$i];
			if ($token[0] === T_ECHO) {
				if (!$start) {
					$str = '';
					$start = strlen($res);
				}

			} elseif ($start && $token[0] === T_CONSTANT_ENCAPSED_STRING && $token[1][0] === "'") {
				$str .= stripslashes(substr($token[1], 1, -1));

			} elseif ($start && $token === ';') {
				if ($str !== '') {
					$res = substr_replace(
						$res,
						'echo ' . ($str === "\n" ? '"\n"' : var_export($str, true)),
						$start,
						strlen($res) - $start,
					);
				}

			} elseif ($token[0] !== T_WHITESPACE) {
				$start = null;
			}

			$res .= is_array($token) ? $token[1] : $token;
		}

		return $res;
	}


	public static function decodeNumber(string $str, &$base = null): int|float|null
	{
		$str = str_replace('_', '', $str);

		if ($str[0] !== '0' || $str === '0') {
			$base = 10;
			return $str + 0;
		} elseif ($str[1] === 'x' || $str[1] === 'X') {
			$base = 16;
			return hexdec($str);
		} elseif ($str[1] === 'b' || $str[1] === 'B') {
			$base = 2;
			return bindec($str);
		} elseif (strpbrk($str, '89')) {
			return null;
		} else {
			$base = 8;
			return octdec($str);
		}
	}


	public static function decodeEscapeSequences(string $str, ?string $quote): string
	{
		if ($quote !== null) {
			$str = str_replace('\\' . $quote, $quote, $str);
		}

		return preg_replace_callback(
			'~\\\\([\\\\$nrtfve]|[xX][0-9a-fA-F]{1,2}|[0-7]{1,3}|u\{([0-9a-fA-F]+)\})~',
			function ($matches) {
				$ch = $matches[1];
				$replacements = [
					'\\' => '\\',
					'$' => '$',
					'n' => "\n",
					'r' => "\r",
					't' => "\t",
					'f' => "\f",
					'v' => "\v",
					'e' => "\x1B",
				];
				if (isset($replacements[$ch])) {
					return $replacements[$ch];
				} elseif ($ch[0] === 'x' || $ch[0] === 'X') {
					return chr(hexdec(substr($ch, 1)));
				} elseif ($ch[0] === 'u') {
					return self::codePointToUtf8(hexdec($matches[2]));
				} else {
					return chr(octdec($ch));
				}
			},
			$str,
		);
	}


	private static function codePointToUtf8(int $num): string
	{
		return match (true) {
			$num <= 0x7F => chr($num),
			$num <= 0x7FF => chr(($num >> 6) + 0xC0) . chr(($num & 0x3F) + 0x80),
			$num <= 0xFFFF => chr(($num >> 12) + 0xE0) . chr((($num >> 6) & 0x3F) + 0x80) . chr(($num & 0x3F) + 0x80),
			$num <= 0x1FFFFF => chr(($num >> 18) + 0xF0) . chr((($num >> 12) & 0x3F) + 0x80)
				. chr((($num >> 6) & 0x3F) + 0x80) . chr(($num & 0x3F) + 0x80),
			default => throw new CompileException('Invalid UTF-8 codepoint escape sequence: Codepoint too large'),
		};
	}
}
