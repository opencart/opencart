<?php
// String
function oc_strlen(string $string) {
	return mb_strlen($string);
}

function oc_strpos(string $string, string $needle, int $offset = 0) {
	return mb_strpos($string, $needle, $offset);
}

function oc_strrpos(string $string, string $needle, int $offset = 0) {
	return mb_strrpos($string, $needle, $offset);
}

function oc_substr(string $string, int $offset, ?int $length = null) {
	return mb_substr($string, $offset, $length);
}

function oc_strtoupper(string $string) {
	return mb_strtoupper($string);
}

function oc_strtolower(string $string) {
	return mb_strtolower($string);
}

// File
function oc_file_move(string $file, string $destination): bool {
	if (!is_file($file)) {
		return false;
	}

	if (is_file($destination)) {
		unlink($destination);
	}

	return rename($file, $destination);
}

function oc_file_copy(string $file, string $destination): bool {
	if (!is_file($file)) {
		return false;
	}

	if (is_file($destination)) {
		unlink($destination);
	}

	return copy($file, $destination);
}

function oc_file_delete(string $file): bool {
	if (!is_file($file)) {
		return false;
	}

	return unlink($file);
}

// Directory
function oc_directory_move(string $directory, string $destination): bool {



	return true;
}

function oc_directory_copy(string $directory, string $destination): bool {
	if (!is_dir($directory)) {
		return false;
	}

	if (is_dir($destination)) {
		return false;
	}

	$files = [];

	// Make path into an array
	$directory = [$directory];

	// While the path array is still populated keep looping through
	while (count($directory) != 0) {
		$next = array_shift($directory);

		if (is_dir($next)) {
			foreach (glob(trim($next, '/') . '/{*,.[!.]*,..?*}', GLOB_BRACE) as $delete) {
				// If directory add to path array
				$directory[] = $delete;
			}
		}

		// Add the file to the files to be deleted array
		$files[] = $next;
	}

	return true;
}

function oc_directory_delete(string $directory): bool {
	$files = [];

	// Make path into an array
	$path = [$directory];

	// While the path array is still populated keep looping through
	while (count($path) != 0) {
		$next = array_shift($path);

		if (is_dir($next)) {
			foreach (glob(trim($next, '/') . '/{*,.[!.]*,..?*}', GLOB_BRACE) as $delete) {
				// If directory add to path array
				$path[] = $delete;
			}
		}

		// Add the file to the files to be deleted array
		$files[] = $next;
	}

	// Reverse sort the file array
	rsort($files);

	foreach ($files as $delete) {
		// If file just delete
		if (is_file($delete)) {
			unlink($delete);
		}

		// If directory use the remove directory function
		if (is_dir($delete)) {
			rmdir($delete);
		}
	}

	return true;
}

// Other
function oc_token(int $length = 32): string {
	return substr(bin2hex(random_bytes($length)), 0, $length);
}

function date_added(string $date): array {
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

// see https://stackoverflow.com/questions/13076480/php-get-actual-maximum-upload-size
function convert_bytes(string $value): int {
    if ( is_numeric( $value ) ) {
        return (int)$value;
    } else {
        $value_length = strlen($value);
        $qty = substr( $value, 0, $value_length - 1 );
        $unit = strtolower( substr( $value, $value_length - 1 ) );
        switch ( $unit ) {
            case 'k':
                $qty *= 1024;
                break;
            case 'm':
                $qty *= 1048576;
                break;
            case 'g':
                $qty *= 1073741824;
                break;
        }
        return (int)$qty;
    }
}
