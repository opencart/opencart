<?php declare(strict_types = 1);

namespace ApiGen\Analyzer;

use PhpParser\ErrorHandler;
use PhpParser\Lexer\Emulative as EmulativeLexer;

use function assert;
use function count;
use function is_array;
use function is_string;

use const T_COMMENT;
use const T_CURLY_OPEN;
use const T_DOC_COMMENT;
use const T_DOLLAR_OPEN_CURLY_BRACES;
use const T_FUNCTION;
use const T_USE;
use const T_WHITESPACE;


class BodySkippingLexer extends EmulativeLexer
{
	protected function postprocessTokens(ErrorHandler $errorHandler): void
	{
		parent::postprocessTokens($errorHandler);

		$tokenCount = count($this->tokens);
		$prevToken = null;
		$level = null;

		for ($i = 0; $i < $tokenCount; $i++) {
			$token = is_array($this->tokens[$i]) ? $this->tokens[$i][0] : $this->tokens[$i];
			assert(is_string($token) || is_int($token));

			if ($level === null) {
				if ($token === T_FUNCTION && $prevToken !== T_USE) {
					$level = 0;
				}

			} else {
				if ($token === '{' || $token === T_CURLY_OPEN || $token === T_DOLLAR_OPEN_CURLY_BRACES) {
					$level++;

				} elseif ($token === '}') {
					$level--;

					if ($level <= 0) {
						$level = null;
					}

				} elseif ($token === ';') {
					if ($level <= 0) {
						$level = null;
					}
				}

				if ($level !== null && $level > ($token === '{' ? 1 : 0)) {
					if (is_array($this->tokens[$i])) {
						$this->tokens[$i][0] = T_WHITESPACE;

					} else {
						$this->tokens[$i] = [T_WHITESPACE, ' '];
					}
				}
			}

			if ($token !== T_WHITESPACE && $token !== T_DOC_COMMENT && $token !== T_COMMENT) {
				$prevToken = $token;
			}
		}
	}
}
