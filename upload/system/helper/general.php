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
function readFileContent($filename) {
	if (file_exists($filename)) {
		$content = file_get_contents($filename);

		return $content;
	}

	return "File not found";
}

// 2. Writing to a file
function writeToFile($filename, $content) {
	$result = file_put_contents($filename, $content);

	return $result !== false;
}

// 3. Appending to a file
function appendToFile($filename, $content) {
	$result = file_put_contents($filename, $content, FILE_APPEND);
	return $result !== false;
}

// 4. Checking if file exists
function checkFileExists($filename) {
	return file_exists($filename);
}

// 5. Getting file size (in bytes)
function getFileSize($filename) {
	return filesize($filename);
}

// 6. Deleting a file
function deleteFile($filename) {
	if (file_exists($filename)) {
		return unlink($filename);
	}
	return false;
}

// Directory Handling Functions

// 1. Creating a directory
function createDirectory($dirname, $permissions = 0777) {
	if (!is_dir($dirname)) {
		return mkdir($dirname, $permissions, true);
	}

	return false;
}

// 2. Removing a directory (must be empty)
function removeDirectory($dirname) {
	if (is_dir($dirname)) {
		return rmdir($dirname);
	}

	return false;
}

// 3. Reading directory contents
function readDirectory($dirname) {
	$contents = [];
	if (is_dir($dirname)) {
		$files = scandir($dirname);
		foreach ($files as $file) {
			if ($file != "." && $file != "..") {
				$contents[] = $file;
			}
		}
	}
	return $contents;
}

// 4. Checking if directory exists
function checkDirectoryExists($dirname) {
	return is_dir($dirname);
}

// 5. Getting file information
function getFileInfo($filename) {
	if (file_exists($filename)) {
		return pathinfo($filename);
	}
	return false;
}