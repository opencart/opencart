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
