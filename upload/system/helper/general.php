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
function oc_file_read($file) {
	if (is_file($file)) {
		return file_get_contents($file);
	}

	return false;
}

// 2. Writing to a file
function oc_file_write($file, $content) {
	return file_put_contents($file, $content) !== false;
}

// 3. Appending to a file
function oc_file_append($filename, $content) {
	return file_put_contents($filename, $content, FILE_APPEND) !== false;
}

// 6. Deleting a file
function oc_file_delete($path) {
	if (is_file($path)) {
		return unlink($path);
	}

	return false;
}

// Directory Handling Functions

// 1. Creating a directory
function oc_directory_create($path, $permissions = 0777) {
	$path_new = '';

	$directories = explode('/', rtrim($path, '/'));

	foreach ($directories as $directory) {
		if (!$path_new) {
			$path_new = $directory;
		} else {
			$path_new = $path_new . '/' . $directory;
		}

		// To fix storage location
		if (!is_dir($path_new . '/') && !mkdir($path_new . '/', $permissions)) {
			return false;
		}
	}

	return true;
}

// 2. Removing a directory
function oc_directory_remove($path) {
	$files = [];

	// Make path into an array
	$directory = [$path];

	// While the path array is still populated keep looping through
	while (count($directory) != 0) {
		$next = array_shift($directory);

		if (is_dir($next)) {
			foreach (glob(rtrim($next, '/') . '/{*,.[!.]*,..?*}', GLOB_BRACE) as $file) {
				// If directory add to path array
				$directory[] = $file;
			}
		}

		// Add the file to the files to be deleted array
		$files[] = $next;
	}

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

	return true;
}

// 3. Reading directory contents
function oc_directory_read($directory) {
	$contents = [];

	if (is_dir($directory)) {
		$files = scandir($directory);

		foreach ($files as $file) {
			if ($file != '.' && $file != '..') {
				$contents[] = $file;
			}
		}
	}
	return $contents;
}