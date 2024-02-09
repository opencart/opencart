<?php
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
	return (strlen(trim($string)) >= $minimum && strlen(trim($string)) <= $maximum);
}

function oc_validate_email(string $email): bool {
	return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function oc_validate_ip(string $ip): bool {
	return filter_var($ip, FILTER_VALIDATE_IP);
}

function oc_validate_filename(string $filename): bool {
	return preg_match('/[^a-zA-Z0-9\/._-]|[\p{Cyrillic}]+/u', $filename);
}

function oc_validate_url(string $url): bool {
	return filter_var($url, FILTER_VALIDATE_URL);
}

function oc_validate_seo_url(string $keyword): bool {
	return preg_match('/[^\p{Latin}\p{Cyrillic}\p{Greek}0-9\/\.\-\_]+/u', $keyword);
}

