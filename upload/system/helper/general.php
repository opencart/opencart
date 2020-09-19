<?php
/* Compatibility function Due to PHP 7.3 only being the PHP version to be able to use samesite attribute */
function oc_setcookie(string $key, string $value, $option = []) {
	if (version_compare(phpversion(), '7.3.0', '>=')) {
		// PHP need to update their setcookie function.
		if (isset($option['max-age'])) {
			$option['expires'] = $option['max-age'];

			unset($option['max-age']);
		}

		setcookie($key, $value, $option);
	} else {
		$string = '';

		if (isset($option['max-age'])) {
			$string .= '; max-age=' . $option['max-age'];
		} else {
			$string .= '; max-age=0';
		}

		if (!empty($option['path'])) {
			$string .= '; path=' . $option['path'];
		}

		if (!empty($option['domain'])) {
			$string .= '; domain=' . $option['domain'];
		}

		if (!empty($option['HttpOnly'])) {
			$string .= '; HttpOnly';
		}

		if (!empty($option['Secure'])) {
			$string .= '; Secure';
		}

		if (isset($option['SameSite'])) {
			$string .= '; SameSite=' . $option['SameSite'];
		}

		header('Set-Cookie: ' . rawurlencode($key) . '=' . rawurlencode($value) . $string);
	}
}

function token($length = 32) {
	if (!isset($length) || intval($length) <= 8) {
		$length = 32;
	}

	if (function_exists('random_bytes')) {
		$token = bin2hex(random_bytes($length));
	}

	if (function_exists('openssl_random_pseudo_bytes')) {
		$token = bin2hex(openssl_random_pseudo_bytes($length));
	}

	return substr($token, -$length, $length);
}

/**
 * Backwards support for timing safe hash string comparisons
 *
 * http://php.net/manual/en/function.hash-equals.php
 */

if (!function_exists('hash_equals')) {
	function hash_equals($known_string, $user_string) {
		$known_string = (string)$known_string;
		$user_string = (string)$user_string;

		if (strlen($known_string) != strlen($user_string)) {
			return false;
		} else {
			$res = $known_string ^ $user_string;
			$ret = 0;

			for ($i = strlen($res) - 1; $i >= 0; $i--) $ret |= ord($res[$i]);

			return !$ret;
		}
	}
}

function date_added($date, $language) {
	$second = time() - strtotime($date);

	if ($second < 10) {
		$date_added = 'just now';
	} elseif ($second) {
		$date_added = $second . ' ' . $language['text_seconds_ago'];
	}

	$minute = floor($second / 60);

	if ($minute == 1) {
		$date_added = $minute . ' ' . $language['text_minute_ago'];
	} elseif ($minute) {
		$date_added = $minute . ' ' . $language['text_minutes_ago'];
	}

	$hour = floor($minute / 60);

	if ($hour == 1) {
		$date_added = $hour . ' ' . $language['text_hour_ago'];
	} elseif ($hour) {
		$date_added = $hour . ' ' . $language['text_hours_ago'];
	}

	$day = floor($hour / 24);

	if ($day == 1) {
		$date_added = $day . ' ' . $language['text_day_ago'];
	} elseif ($day) {
		$date_added = $day . ' ' . $language['text_days_ago'];
	}

	$week = floor($day / 7);

	if ($week == 1) {
		$date_added = $week . ' ' . $language['text_week_ago'];
	} elseif ($week) {
		$date_added = $week . ' ' . $language['text_weeks_ago'];
	}

	$month = floor($week / 4);

	if ($month == 1) {
		$date_added = $month . ' ' . $language['text_month_ago'];
	} elseif ($month) {
		$date_added = $month . ' ' . $language['text_months_ago'];
	}

	$year = floor($week / 52.1429);

	if ($year == 1) {
		$date_added = $year . ' ' . $language['text_year_ago'];
	} elseif ($year) {
		$date_added = $year . ' ' . $language['text_years_ago'];
	}

	return $date_added;
}
