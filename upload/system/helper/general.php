<?php
/* Compatibility function Due to PHP 7.3 only being the PHP version to be able to use samesite attribute */
function oc_setcookie(string $key, string $value, $option = []) {
	if (version_compare(phpversion(), '7.3.0', '>=')) {
		// PHP needs to update their setcookie function.
		setcookie($key, $value, $option);
	} else {
		$string = '';

		if (isset($option['expires'])) {
			$string .= '; expires=' . $option['expires'];
		} else {
			$string .= '; expires=0';
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
		$date_added = sprintf($language['text_just_now'], $second);
	} elseif ($second) {
		$date_added = sprintf($language['text_seconds_ago'], $second);
	}

	$minute = floor($second / 60);

	if ($minute == 1) {
		$date_added = sprintf($language['text_minute_ago'], $minute);
	} elseif ($minute) {
		$date_added = sprintf($language['text_minutes_ago'], $minute);
	}

	$hour = floor($minute / 60);

	if ($hour == 1) {
		$date_added = sprintf($language['text_hour_ago'], $hour);
	} elseif ($hour) {
		$date_added = sprintf($language['text_hours_ago'], $hour);
	}

	$day = floor($hour / 24);

	if ($day == 1) {
		$date_added = sprintf($language['text_day_ago'], $day);
	} elseif ($day) {
		$date_added = sprintf($language['text_days_ago'], $day);
	}

	$week = floor($day / 7);

	if ($week == 1) {
		$date_added = sprintf($language['text_week_ago'], $week);
	} elseif ($week) {
		$date_added = sprintf($language['text_weeks_ago'], $week);
	}

	$month = floor($week / 4);

	if ($month == 1) {
		$date_added = sprintf($language['text_month_ago'], $month);
	} elseif ($month) {
		$date_added = sprintf($language['text_months_ago'], $month);
	}

	$year = floor($week / 52.1429);

	if ($year == 1) {
		$date_added = sprintf($language['text_year_ago'], $year);
	} elseif ($year) {
		$date_added = sprintf($language['text_years_ago'], $year);
	}

	return $date_added;
}

function get_path($source, $dir) {
	return utf8_substr(str_replace('\\', '/', realpath($source)), 0, utf8_strlen($dir));
}
