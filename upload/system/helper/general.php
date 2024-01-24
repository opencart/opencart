<?php
// String
function oc_strlen(string $string): int {
	return mb_strlen($string);
}

/**
 * @param string $string
 * @param string $needle
 * @param int    $offset
 *
 * @return false|int
 */
function oc_strpos(string $string, string $needle, int $offset = 0) {
	return mb_strpos($string, $needle, $offset);
}

/**
 * @param string $string
 * @param string $needle
 * @param int    $offset
 *
 * @return false|int
 */
function oc_strrpos(string $string, string $needle, int $offset = 0) {
	return mb_strrpos($string, $needle, $offset);
}

function oc_substr(string $string, int $offset, ?int $length = null): string {
	return mb_substr($string, $offset, $length);
}

function oc_strtoupper(string $string): string {
	return mb_strtoupper($string);
}

function oc_strtolower(string $string): string {
	return mb_strtolower($string);
}

// Email
function oc_filter_email(string $email): bool {
	if (oc_strrpos($email, '@') === false) return false;

	$local = oc_substr($email, 0, oc_strrpos($email, '@'));

	$domain = oc_substr($email, (oc_strrpos($email, '@') + 1));

	$email = $local . '@' . oc_punycode($domain);

	return filter_var($email, FILTER_VALIDATE_EMAIL, FILTER_FLAG_EMAIL_UNICODE);
}

// Url
function oc_punycode(string $string): string {
	return idn_to_ascii($string, IDNA_NONTRANSITIONAL_TO_ASCII, INTL_IDNA_VARIANT_UTS46);
}

// Other
function oc_token(int $length = 32): string {
	return substr(bin2hex(random_bytes($length)), 0, $length);
}

// Pre PHP8 compatibility
if (!function_exists('str_starts_with')) {
	function str_starts_with(string $string, string $find): bool {
		$substring = substr($string, strlen($find));

		if ($substring === $find) {
			return true;
		} else {
			return false;
		}
	}
}

if (!function_exists('str_ends_with')) {
	function str_ends_with(string $string, string $find): bool {
		return substr($string, -strlen($find)) === $find;
	}
}
