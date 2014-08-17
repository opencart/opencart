<?php
/**
 * Throws warnings if a file contains trailing whitespace.
 *
 * @category PHP
 * @package  PHP_CodeSniffer-Symfony2
 * @author   Justin Hileman <justin@shopopensky.com>
 * @license  http://spdx.org/licenses/MIT MIT License
 * @link     https://github.com/opensky/Symfony2-coding-standard
 */
class Opencart_Sniffs_Files_WhitespaceSniff implements PHP_CodeSniffer_Sniff {
	public $supportedTokenizers = array(
		'PHP',
		'JS',
		'CSS',
	);

	public function register() {
		return array(T_WHITESPACE);
	}

	public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();

		// Make sure this is trailing whitespace.
		$line = $tokens[$stackPtr]['line'];
		if (($stackPtr < count($tokens) - 1) && $tokens[($stackPtr + 1)]['line'] === $line) {
			return;
		}

		if (strpos($tokens[$stackPtr]['content'], "\n") > 0 || strpos($tokens[$stackPtr]['content'], "\r") > 0) {
			$warning = 'Trim any trailing whitespace';
			$phpcsFile->addWarning($warning, $stackPtr);
		}

	}
}