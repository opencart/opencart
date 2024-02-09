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