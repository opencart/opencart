<?php

namespace Opencart\System\Library\Database\Parser;

/**
 * Tokenizes MySQL/MariaDB SQL for placeholder processing.
 *
 * This is a lightweight lexer, not a complete SQL grammar parser.
 */
final class SqlParser {
	public function __construct(
		private readonly bool $backslashEscapes = true
	) {
	}

	/**
	 * @return list<array{type: 'text'|'named'|'positional', value: string}>
	 */
	public function tokenize(string $sql): array {
		$tokens = [];
		$text = '';
		$offset = 0;
		$length = strlen($sql);
		$insideExecutableComment = false;

		while ($offset < $length) {
			// An executable comment is SQL to MySQL, so scan its body.
			if ($insideExecutableComment && substr($sql, $offset, 2) === '*/') {
				$text .= '*/';
				$offset += 2;
				$insideExecutableComment = false;
				continue;
			}

			// MySQL executable comment: /*! ... */.
			if (!$insideExecutableComment && substr($sql, $offset, 3) === '/*!') {
				$text .= '/*!';
				$offset += 3;
				$insideExecutableComment = true;
				continue;
			}

			// MariaDB executable comment: /*M! ... */.
			if (!$insideExecutableComment && substr($sql, $offset, 4) === '/*M!') {
				$text .= '/*M!';
				$offset += 4;
				$insideExecutableComment = true;
				continue;
			}

			$token = $this->readToken($sql, $offset);

			if ($token === null) {
				$text .= $sql[$offset];
				$offset++;
				continue;
			}

			if ($token['type'] === 'text') {
				$text .= $token['value'];
				continue;
			}

			$this->flushText($tokens, $text);
			$tokens[] = $token;
		}

		if ($insideExecutableComment) {
			throw new \InvalidArgumentException(
				'Unclosed MySQL executable comment'
			);
		}

		$this->flushText($tokens, $text);

		return $tokens;
	}

	/**
	 * Read one special SQL token at the current offset.
	 *
	 * @return array{type: 'text'|'named'|'positional', value: string}|null
	 */
	private function readToken(string $sql, int &$offset): ?array {
		return match ($sql[$offset]) {
			"'", '"', '`' => [
				'type' => 'text',
				'value' => $this->readQuoted($sql, $offset)
			],
			'#' => [
				'type' => 'text',
				'value' => $this->readLineComment($sql, $offset)
			],
			'-' => $this->readDashComment($sql, $offset),
			'/' => $this->readBlockCommentToken($sql, $offset),
			':' => $this->readNamedParameter($sql, $offset),
			'?' => $this->readPositionalParameter($offset),
			default => null
		};
	}

	private function readQuoted(string $sql, int &$offset): string {
		$quote = $sql[$offset];
		$start = $offset;
		$length = strlen($sql);
		$offset++;

		while ($offset < $length) {
			if (
				$quote !== '`' &&
				$this->backslashEscapes &&
				$sql[$offset] === '\\' &&
				$offset + 1 < $length
			) {
				$offset += 2;
				continue;
			}

			if ($sql[$offset] === $quote) {
				// SQL escapes a quote by doubling its delimiter.
				if (
					$offset + 1 < $length &&
					$sql[$offset + 1] === $quote
				) {
					$offset += 2;
					continue;
				}

				$offset++;

				return substr($sql, $start, $offset - $start);
			}

			$offset++;
		}

		throw new \InvalidArgumentException('Unclosed SQL quoted expression');
	}

	private function readLineComment(string $sql, int &$offset): string {
		$start = $offset;
		$length = strlen($sql);

		while (
			$offset < $length &&
			$sql[$offset] !== "\n" &&
			$sql[$offset] !== "\r"
		) {
			$offset++;
		}

		return substr($sql, $start, $offset - $start);
	}

	private function readDashComment(string $sql, int &$offset): ?array {
		if (substr($sql, $offset, 2) !== '--') {
			return null;
		}

		$next = $sql[$offset + 2] ?? null;

		// MySQL requires whitespace/control character after the two dashes.
		if ($next === null || ord($next) > 32) {
			return null;
		}

		return [
			'type' => 'text',
			'value' => $this->readLineComment($sql, $offset)
		];
	}

	private function readBlockCommentToken(string $sql, int &$offset): ?array {
		if (substr($sql, $offset, 2) !== '/*') {
			return null;
		}

		$start = $offset;
		$end = strpos($sql, '*/', $offset + 2);

		if ($end === false) {
			throw new \InvalidArgumentException('Unclosed SQL block comment');
		}

		$offset = $end + 2;

		return [
			'type' => 'text',
			'value' => substr($sql, $start, $offset - $start)
		];
	}

	private function readNamedParameter(string $sql, int &$offset): ?array {
		$length = strlen($sql);

		if (
			($offset > 0 && $sql[$offset - 1] === ':') ||
			$offset + 1 >= $length ||
			!$this->isNameCharacter($sql[$offset + 1])
		) {
			return null;
		}

		$start = $offset + 1;
		$end = $start;

		while ($end < $length && $this->isNameCharacter($sql[$end])) {
			$end++;
		}

		$name = substr($sql, $start, $end - $start);
		$offset = $end;

		return [
			'type' => 'named',
			'value' => $name
		];
	}

	private function isNameCharacter(string $char): bool {
		return ctype_alnum($char) || $char === '_';
	}

	private function readPositionalParameter(int &$offset): array {
		$offset++;

		return [
			'type' => 'positional',
			'value' => '?'
		];
	}

	private function flushText(array &$tokens, string &$text): void {
		if ($text === '') {
			return;
		}

		$tokens[] = [
			'type' => 'text',
			'value' => $text
		];

		$text = '';
	}
}
