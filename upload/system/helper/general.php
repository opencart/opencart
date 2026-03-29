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
		if (array_key_exists($header, $_SERVER)) {
			$ip = $_SERVER[$header];

			// This line might or might not be used.
			$ip = trim(explode(',', $ip)[0]);

			return $ip;
		}
	}

	return $_SERVER['REMOTE_ADDR'];
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
 * Emulation of GLOB_BRACE for Alpine/musl environments
 *
 * @param string $pattern Pattern with curly braces
 * @param int $flags Flags for glob()
 * @return array Array of matches
 */
function oc_glob($pattern, $flags = 0) {
    // If there are no curly braces — just call glob()
    if (strpos($pattern, '{') === false) {
        return glob($pattern, $flags);
    }

    // Find the first pair of {}
    $matches = [];
    if (preg_match('/\{([^}]+)\}/', $pattern, $m)) {
        // Split the content inside braces by comma
        $options = explode(',', $m[1]);
        foreach ($options as $opt) {
            // Replace the whole {a,b,c} with one option
            $newPattern = str_replace($m[0], $opt, $pattern);
            // Recursively call oc_glob in case of nested braces
            $matches = array_merge($matches, oc_glob($newPattern, $flags));
        }
    }

    // Remove duplicates and sort results
    $matches = array_unique($matches);
    sort($matches);

    return $matches;
}

// Pre PHP8 compatibility
/*
 * @param string $string
 * @param string $find
 *
 * @return bool
 */
if (!function_exists('str_starts_with')) {
	function str_starts_with(string $string, string $find): bool {
		$substring = substr($string, 0, strlen($find));

		if ($substring === $find) {
			return true;
		} else {
			return false;
		}
	}
}

/*
 * @param string $string
 * @param string $find
 *
 * @return bool
 */
if (!function_exists('str_ends_with')) {
	function str_ends_with(string $string, string $find): bool {
		return substr($string, -strlen($find)) === $find;
	}
}

/*
 * @param string $string
 * @param string $find
 *
 * @return bool
 */
if (!function_exists('str_contains')) {
	function str_contains(string $string, string $find): bool {
		return $find === '' || strpos($string, $find) !== false;
	}
}
