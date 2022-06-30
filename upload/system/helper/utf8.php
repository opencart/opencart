<?php
//namespace Opencart\System\Helper;
mb_internal_encoding('UTF-8');

function utf8_strlen(string $string) {
	return mb_strlen($string);
}

function utf8_strpos(string $string, string $needle, int $offset = 0) {
	return mb_strpos($string, $needle, $offset);
}

function utf8_strrpos(string $string, string $needle, int $offset = 0) {
	return mb_strrpos($string, $needle, $offset);
}

function utf8_substr(string $string, int $offset, ?int $length = null) {
	return mb_substr($string, $offset, $length);
}

function utf8_strtoupper(string $string) {
	return mb_strtoupper($string);
}

function utf8_strtolower(string $string) {
	return mb_strtolower($string);
}