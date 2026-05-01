<?php
/**
 * Other
 *
 * @param int $length
 *
 * @return string
 */
function oc_token(int $length = 32): string {
	return substr(bin2hex(random_bytes($length)), 0, $length);
}

/** @return string */
function oc_get_ip(): string {
	$headers = [
		'HTTP_CF_CONNECTING_IP', // CloudFlare
		'HTTP_X_FORWARDED_FOR',  // AWS LB and other reverse-proxies
		'HTTP_X_REAL_IP',
		'HTTP_X_CLIENT_IP',
		'HTTP_CLIENT_IP',
		'HTTP_X_CLUSTER_CLIENT_IP',
	];

	foreach ($headers as $header) {
		if (!array_key_exists($header, $_SERVER)) {
			continue;
		}

		$ip = trim(explode(',', $_SERVER[$header])[0]);

		if ($ip !== '' && filter_var($ip, FILTER_VALIDATE_IP) !== false) {
			return $ip;
		}
	}

	return $_SERVER['REMOTE_ADDR'] ?? '';
}

// Sting functions

/**
 * @param string $string
 *
 * @return int
 */
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

/**
 * @param string $string
 * @param int    $offset
 * @param ?int   $length
 *
 * @return string
 */
function oc_substr(string $string, int $offset, ?int $length = null): string {
	return mb_substr($string, $offset, $length);
}

/**
 * @param string $string
 *
 * @return string
 */
function oc_strtoupper(string $string): string {
	return mb_strtoupper($string);
}

/**
 * @param string $string
 *
 * @return string
 */
function oc_strtolower(string $string): string {
	return mb_strtolower($string);
}

/**
 * @param string $pattern
 * @param int    $flags
 *
 * @return array<int, string>
 */
function oc_glob(string $pattern, int $flags = 0): array {
	if (strpos($pattern, '{') === false) {
		$result = glob($pattern, $flags);

		return is_array($result) ? $result : [];
	}

	$matches = [];
	if (preg_match('/\{([^}]+)\}/', $pattern, $m)) {
		$options = explode(',', $m[1]);
		foreach ($options as $opt) {
			$newPattern = str_replace($m[0], $opt, $pattern);
			// Now safe because oc_glob always returns an array
			$matches = array_merge($matches, oc_glob($newPattern, $flags));
		}
	}

	$matches = array_unique($matches);
	sort($matches);

	return $matches;
}
