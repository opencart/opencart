<?php
///namespace Opencart\System\Helper;
function token($length = 32) {
	if (intval($length) <= 8) {
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

function date_added($date) {
	$second = time() - strtotime($date);

	if ($second < 10) {
		$code = 'second';
		$date_added = $second;
	} elseif ($second) {
		$code = 'seconds';
		$date_added = $second;
	}

	$minute = floor($second / 60);

	if ($minute == 1) {
		$code = 'minute';
		$date_added = $minute;
	} elseif ($minute) {
		$code = 'minutes';
		$date_added = $minute;
	}

	$hour = floor($minute / 60);

	if ($hour == 1) {
		$code = 'hour';
		$date_added = $hour;
	} elseif ($hour) {
		$code = 'hours';
		$date_added = $hour;
	}

	$day = floor($hour / 24);

	if ($day == 1) {
		$code = 'day';
		$date_added = $day;
	} elseif ($day) {
		$code = 'days';
		$date_added = $day;
	}

	$week = floor($day / 7);

	if ($week == 1) {
		$code = 'week';
		$date_added = $week;
	} elseif ($week) {
		$code = 'weeks';
		$date_added = $week;
	}

	$month = floor($week / 4);

	if ($month == 1) {
		$code = 'month';
		$date_added = $month;
	} elseif ($month) {
		$code = 'months';
		$date_added = $month;
	}

	$year = floor($week / 52.1429);

	if ($year == 1) {
		$code = 'year';
		$date_added = $year;
	} elseif ($year) {
		$code = 'years';
		$date_added = $year;
	}

	return [$code, $date_added];
}