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
 * @return int|false
 */
function oc_strpos(string $string, string $needle, int $offset = 0): int|false {
	return mb_strpos($string, $needle, $offset);
}

/**
 * @param string $string
 * @param string $needle
 * @param int    $offset
 *
 * @return int|false
 */
function oc_strrpos(string $string, string $needle, int $offset = 0): int|false {
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

// File Handling Functions

// 1. Reading a file
function oc_file_read(string $file): string|false {
	if (is_file($file)) {
		return file_get_contents($file);
	}

	return false;
}

// 2. Writing to a file
function oc_file_write(string $file, string $content, bool $append = false): bool {
	if ($append) {
		return file_put_contents($file, $content, FILE_APPEND) !== false;
	} else {
		return file_put_contents($file, $content) !== false;
	}
}

// 3. Deleting a file
function oc_file_delete(string $file): bool {
	if (is_file($file)) {
		return unlink($file);
	}

	return false;
}

// Directory Handling Functions

// 1. Reading directory contents
/**
 * @return list<string>
 */
function oc_directory_read(string $directory, bool $recursive = false, string $regex = ''): array {
	$files = [];

	$directory = str_replace('\\', '/', realpath($directory));

	if (is_dir($directory)) {
		$stack = [rtrim($directory, '/')];

		while (count($stack) != 0) {
			$next = array_shift($stack);

			$results = scandir($next);

			foreach ($results as $result) {
				if ($result == '.' || $result == '..') {
					continue;
				}

				$file = $next . '/' . $result;

				if (is_dir($file)) {
					if ($recursive) {
						$stack[] = $file;
					}

					$file = $file . '/';
				}

				// Add the file to the files to be deleted array
				if ($regex && !preg_match($regex, $file)) {
					continue;
				}

				$files[] = $file;
			}
		}

		sort($files);
	}

	return $files;
}

// 2. Creating a directory
/**
 * Creates a directory recursively.
 *
 * @param string $path       the path of the directory to create
 * @param int    $permission the directory permissions
 *
 * @return bool true on success or false on failure
 */
function oc_directory_create(string $path, int $permission = 0777): bool {
	if (is_dir($path)) {
		return true;
	}

	return @mkdir($path, $permission, true) && is_dir($path);
}

// 3. Removing a directory
function oc_directory_delete(string $directory): bool {
	if (!is_dir($directory)) {
		return false;
	}

	$files = oc_directory_read($directory, true);

	// Reverse sort the file array
	rsort($files);

	foreach ($files as $file) {
		// If file just delete
		if (is_file($file)) {
			unlink($file);
		}

		// If directory use the remove directory function
		if (is_dir($file)) {
			rmdir($file);
		}
	}

	if (is_dir($directory)) {
		rmdir($directory);
	}

	return true;
}

/**
 * Validate Length
 *
 * @param string $string
 * @param int    $minimum
 * @param int    $maximum
 *
 * @return bool
 */
function oc_validate_length(string $string, int $minimum, int $maximum): bool {
	return oc_strlen(trim($string)) >= $minimum && oc_strlen(trim($string)) <= $maximum;
}

/**
 * Validate Email
 *
 * @param string $email The email to validate
 *
 * @return bool
 */
function oc_validate_email(string $email): bool {
	if (oc_strlen($email) > 96) {
		return false;
	}

	if (oc_strrpos($email, '@') === false) {
		return false;
	}

	if (function_exists('idn_to_ascii')) {
		$local = oc_substr($email, 0, oc_strrpos($email, '@'));

		$domain = oc_substr($email, (oc_strrpos($email, '@') + 1));

		$email = $local . '@' . idn_to_ascii($domain, IDNA_NONTRANSITIONAL_TO_ASCII, INTL_IDNA_VARIANT_UTS46);
	}

	return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Validate Regular Expression
 *
 * @param string $string  The string to validate
 * @param string $pattern The regular expression pattern
 *
 * @return bool
 */
function oc_validate_regex(string $string, string $pattern): bool {
	$option = ['regexp' => html_entity_decode($pattern, ENT_QUOTES, 'UTF-8')];

	return filter_var($string, FILTER_VALIDATE_REGEXP, ['options' => $option]);
}

/**
 * Validate IP
 *
 * @param string $ip
 *
 * @return bool
 */
function oc_validate_ip(string $ip): bool {
	return filter_var($ip, FILTER_VALIDATE_IP);
}

/**
 * Validate Filename
 *
 * @param string $filename
 *
 * @return bool
 */
function oc_validate_filename(string $filename): bool {
	return !preg_match('/[^a-zA-Z\p{Cyrillic}0-9\.\-\_]+/u', $filename);
}

/**
 * Validate URL
 *
 * @param string $url
 *
 * @return bool
 */
function oc_validate_url(string $url): bool {
	return filter_var($url, FILTER_VALIDATE_URL);
}

/**
 * Validate SEO URL
 *
 * @param string $keyword
 *
 * @return bool
 */
function oc_validate_path(string $keyword): bool {
	return !preg_match('/[^\p{Latin}\p{Cyrillic}\p{Greek}0-9\/\-\_]+/u', $keyword);
}
