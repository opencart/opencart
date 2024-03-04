<?php

/**
 * This file is part of the Nette Tester.
 * Copyright (c) 2009 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Tester\CodeCoverage;


/**
 * Parses PHP source code and returns:
 * - the start/end line information about functions, classes, interfaces, traits and their methods
 * - the count of code lines
 * - the count of commented code lines
 *
 * @internal
 */
class PhpParser
{
	/**
	 * Returned structure is:
	 *     stdClass {
	 *         linesOfCode: int,
	 *         linesOfComments: int,
	 *         functions: [functionName => $functionInfo],
	 *         classes: [className => $info],
	 *         traits: [traitName => $info],
	 *         interfaces: [interfaceName => $info],
	 *     }
	 *
	 * where $functionInfo is:
	 *     stdClass {
	 *         start: int,
	 *         end: int
	 *     }
	 *
	 * and $info is:
	 *     stdClass {
	 *         start: int,
	 *         end: int,
	 *         methods: [methodName => $methodInfo]
	 *     }
	 *
	 * where $methodInfo is:
	 *     stdClass {
	 *         start: int,
	 *         end: int,
	 *         visibility: public|protected|private
	 *     }
	 */
	public function parse(string $code): \stdClass
	{
		$tokens = token_get_all($code, TOKEN_PARSE);

		$level = $classLevel = $functionLevel = null;
		$namespace = '';
		$line = 1;

		$result = (object) [
			'linesOfCode' => max(1, substr_count($code, "\n")),
			'linesOfComments' => 0,
			'functions' => [],
			'classes' => [],
			'traits' => [],
			'interfaces' => [],
		];

		while ($token = current($tokens)) {
			next($tokens);
			if (is_array($token)) {
				$line = $token[2];
			}

			switch (is_array($token) ? $token[0] : $token) {
				case T_NAMESPACE:
					$namespace = self::fetch($tokens, [T_STRING, T_NAME_QUALIFIED]);
					$namespace = ltrim($namespace . '\\', '\\');
					break;

				case T_CLASS:
				case T_INTERFACE:
				case T_TRAIT:
					if ($name = self::fetch($tokens, T_STRING)) {
						if ($token[0] === T_CLASS) {
							$class = &$result->classes[$namespace . $name];
						} elseif ($token[0] === T_INTERFACE) {
							$class = &$result->interfaces[$namespace . $name];
						} else {
							$class = &$result->traits[$namespace . $name];
						}

						$classLevel = $level + 1;
						$class = (object) [
							'start' => $line,
							'end' => null,
							'methods' => [],
						];
					}

					break;

				case T_PUBLIC:
				case T_PROTECTED:
				case T_PRIVATE:
					$visibility = $token[1];
					break;

				case T_ABSTRACT:
					$isAbstract = true;
					break;

				case T_FUNCTION:
					if (($name = self::fetch($tokens, T_STRING)) && !isset($isAbstract)) {
						if (isset($class) && $level === $classLevel) {
							$function = &$class->methods[$name];
							$function = (object) [
								'start' => $line,
								'end' => null,
								'visibility' => $visibility ?? 'public',
							];

						} else {
							$function = &$result->functions[$namespace . $name];
							$function = (object) [
								'start' => $line,
								'end' => null,
							];
						}

						$functionLevel = $level + 1;
					}

					unset($visibility, $isAbstract);
					break;

				case T_CURLY_OPEN:
				case T_DOLLAR_OPEN_CURLY_BRACES:
				case '{':
					$level++;
					break;

				case '}':
					if (isset($function) && $level === $functionLevel) {
						$function->end = $line;
						unset($function);

					} elseif (isset($class) && $level === $classLevel) {
						$class->end = $line;
						unset($class);
					}

					$level--;
					break;

				case T_COMMENT:
				case T_DOC_COMMENT:
					$result->linesOfComments += substr_count(trim($token[1]), "\n") + 1;
					// break omitted

				case T_WHITESPACE:
				case T_CONSTANT_ENCAPSED_STRING:
					$line += substr_count($token[1], "\n");
					break;
			}
		}

		return $result;
	}


	private static function fetch(array &$tokens, array|int $take): ?string
	{
		$res = null;
		while ($token = current($tokens)) {
			[$token, $s] = is_array($token) ? $token : [$token, $token];
			if (in_array($token, (array) $take, true)) {
				$res .= $s;
			} elseif (!in_array($token, [T_DOC_COMMENT, T_WHITESPACE, T_COMMENT], true)) {
				break;
			}

			next($tokens);
		}

		return $res;
	}
}
