<?php
namespace Opencart\System\Helper\Utf8;
mb_internal_encoding('UTF-8');

function strlen(string $string): int {
	return mb_strlen($string);
}

/**
 * @param string $string
 * @param string $needle
 * @param int    $offset
 *
 * @return false|int
 */
function strpos(string $string, string $needle, int $offset = 0) {
	return mb_strpos($string, $needle, $offset);
}

/**
 * @param string $string
 * @param string $needle
 * @param int    $offset
 *
 * @return false|int
 */
function strrpos(string $string, string $needle, int $offset = 0) {
	return mb_strrpos($string, $needle, $offset);
}

function substr(string $string, int $offset, ?int $length = null): string {
	return mb_substr($string, $offset, $length);
}

function strtoupper(string $string): string {
	return mb_strtoupper($string);
}

function strtolower(string $string): string {
	return mb_strtolower($string);
}
