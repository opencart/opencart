<?php

//
// Command line tool for installing opencart
// Author: Vineet Naik <vineet.naik@kodeplay.com> <naikvin@gmail.com>
//
// (Currently tested on linux only)
//
// Usage:
//
//   cd install
//   php cli_install.php install --db_host localhost \
//                               --db_user root \
//                               --db_password pass \
//                               --db_name opencart \
//                               --username admin \
//                               --password admin \
//                               --email youremail@example.com \
//                               --agree_tnc yes \
//                               --http_server http://localhost/opencart
//

ini_set('display_errors', 1);
error_reporting(E_ALL);

// DIR
define('DIR_APPLICATION', str_replace('\'', '/', realpath(dirname(__FILE__))) . '/');
define('DIR_SYSTEM', str_replace('\'', '/', realpath(dirname(__FILE__) . '/../')) . '/system/');
define('DIR_OPENCART', str_replace('\'', '/', realpath(DIR_APPLICATION . '../')) . '/');
define('DIR_DATABASE', DIR_SYSTEM . 'database/');
define('DIR_LANGUAGE', DIR_APPLICATION . 'language/');
define('DIR_TEMPLATE', DIR_APPLICATION . 'view/template/');
define('DIR_CONFIG', DIR_SYSTEM . 'config/');

// Startup
require_once(DIR_SYSTEM . 'startup.php');

// Registry
$registry = new Registry();

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);


function handleError($errno, $errstr, $errfile, $errline, array $errcontext) {
	// error was suppressed with the @-operator
	if (0 === error_reporting()) {
		return false;
	}
	throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}

set_error_handler('handleError');


function usage() {
	echo "Usage:\n";
	echo "======\n";
	echo "\n";
	$options = implode(" ", array('--db_driver', 'mysqli',
								'--db_host', 'localhost',
								'--db_user', 'root',
								'--db_password', 'pass',
								'--db_name', 'opencart',
								'--username', 'admin',
								'--password', 'admin',
								'--email', 'youremail@example.com',
								'--agree_tnc', 'yes',
								'--http_server', 'http://localhost/opencart'));
	echo 'php cli_install.php install ' . $options . "\n\n";
}


function get_options($argv) {
	$defaults = array(
		'db_driver' => 'mysqli',
		'db_host' => 'localhost',
		'db_name' => 'opencart',
		'db_prefix' => '',
		'username' => 'admin',
		'agree_tnc' => 'no',
	);

	$options = array();
	$total = count($argv);
	for ($i=0; $i < $total; $i=$i+2) {
		$is_flag = preg_match('/^--(.*)$/', $argv[$i], $match);
		if (!$is_flag) {
			throw new Exception($argv[$i] . ' found in command line args instead of a valid option name starting with \'--\'');
		}
		$options[$match[1]] = $argv[$i+1];
	}
	return array_merge($defaults, $options);
}


function valid($options) {
	$required = array(
		'db_driver',
		'db_host',
		'db_user',
		'db_password',
		'db_name',
		'db_prefix',
		'username',
		'password',
		'email',
		'agree_tnc',
		'http_server',
	);
	$missing = array();
	foreach ($required as $r) {
		if (!array_key_exists($r, $options)) {
			$missing[] = $r;
		}
	}
	if ($options['agree_tnc'] !== 'yes') {
		$missing[] = 'agree_tnc (should be yes)';
	}
	$valid = count($missing) === 0 && $options['agree_tnc'] === 'yes';
	return array($valid, $missing);
}


function install($options) {
	$check = check_requirements();
	if ($check[0]) {
		setup_mysql($options);
		write_config_files($options);
		dir_permissions();
	} else {
		echo 'FAILED! Pre-installation check failed: ' . $check[1] . "\n\n";
		exit(1);
	}
}


function check_requirements() {
	$error = null;
	if (phpversion() < '5.0') {
		$error = 'Warning: You need to use PHP5 or above for OpenCart to work!';
	}

	if (!ini_get('file_uploads')) {
		$error = 'Warning: file_uploads needs to be enabled!';
	}

	if (ini_get('session.auto_start')) {
		$error = 'Warning: OpenCart will not work with session.auto_start enabled!';
	}

	if (!extension_loaded('mysql')) {
		$error = 'Warning: MySQL extension needs to be loaded for OpenCart to work!';
	}

	if (!extension_loaded('gd')) {
		$error = 'Warning: GD extension needs to be loaded for OpenCart to work!';
	}

	if (!extension_loaded('curl')) {
		$error = 'Warning: CURL extension needs to be loaded for OpenCart to work!';
	}

	if (!function_exists('mcrypt_encrypt')) {
		$error = 'Warning: mCrypt extension needs to be loaded for OpenCart to work!';
	}

	if (!extension_loaded('zlib')) {
		$error = 'Warning: ZLIB extension needs to be loaded for OpenCart to work!';
	}

	if (!is_writable(DIR_OPENCART . 'config.php')) {
		$error = 'Warning: config.php needs to be writable for OpenCart to be installed!';
	}

	if (!is_writable(DIR_OPENCART . 'admin/config.php')) {
		$error = 'Warning: admin/config.php needs to be writable for OpenCart to be installed!';
	}

	if (!is_writable(DIR_SYSTEM . 'cache')) {
		$error = 'Warning: Cache directory needs to be writable for OpenCart to work!';
	}

	if (!is_writable(DIR_SYSTEM . 'logs')) {
		$error = 'Warning: Logs directory needs to be writable for OpenCart to work!';
	}

	if (!is_writable(DIR_OPENCART . 'image')) {
		$error = 'Warning: Image directory needs to be writable for OpenCart to work!';
	}

	if (!is_writable(DIR_OPENCART . 'image/cache')) {
		$error = 'Warning: Image cache directory needs to be writable for OpenCart to work!';
	}

	if (!is_writable(DIR_OPENCART . 'image/data')) {
		$error = 'Warning: Image data directory needs to be writable for OpenCart to work!';
	}

	if (!is_writable(DIR_OPENCART . 'download')) {
		$error = 'Warning: Download directory needs to be writable for OpenCart to work!';
	}

	return array($error === null, $error);
}


function setup_mysql($dbdata) {
	global $loader, $registry;
	$loader->model('install');
	$model = $registry->get('model_install');
	$model->database($dbdata);
}


function write_config_files($options) {
	$output  = '<?php' . "\n";
	$output .= '// HTTP' . "\n";
	$output .= 'define(\'HTTP_SERVER\', \'' . $options['http_server'] . '\');' . "\n";
	$output .= 'define(\'HTTP_IMAGE\', \'' . $options['http_server'] . 'image/\');' . "\n";
	$output .= 'define(\'HTTP_ADMIN\', \'' . $options['http_server'] . 'admin/\');' . "\n\n";

	$output .= '// HTTPS' . "\n";
	$output .= 'define(\'HTTPS_SERVER\', \'' . $options['http_server'] . '\');' . "\n";
	$output .= 'define(\'HTTPS_IMAGE\', \'' . $options['http_server'] . 'image/\');' . "\n\n";

	$output .= '// DIR' . "\n";
	$output .= 'define(\'DIR_APPLICATION\', \'' . DIR_OPENCART . 'catalog/\');' . "\n";
	$output .= 'define(\'DIR_SYSTEM\', \'' . DIR_OPENCART. 'system/\');' . "\n";
	$output .= 'define(\'DIR_DATABASE\', \'' . DIR_OPENCART . 'system/database/\');' . "\n";
	$output .= 'define(\'DIR_LANGUAGE\', \'' . DIR_OPENCART . 'catalog/language/\');' . "\n";
	$output .= 'define(\'DIR_TEMPLATE\', \'' . DIR_OPENCART . 'catalog/view/theme/\');' . "\n";
	$output .= 'define(\'DIR_CONFIG\', \'' . DIR_OPENCART . 'system/config/\');' . "\n";
	$output .= 'define(\'DIR_IMAGE\', \'' . DIR_OPENCART . 'image/\');' . "\n";
	$output .= 'define(\'DIR_CACHE\', \'' . DIR_OPENCART . 'system/cache/\');' . "\n";
	$output .= 'define(\'DIR_DOWNLOAD\', \'' . DIR_OPENCART . 'download/\');' . "\n";
	$output .= 'define(\'DIR_LOGS\', \'' . DIR_OPENCART . 'system/logs/\');' . "\n\n";

	$output .= '// DB' . "\n";
	$output .= 'define(\'DB_DRIVER\', \'mysql\');' . "\n";
	$output .= 'define(\'DB_HOSTNAME\', \'' . addslashes($options['db_host']) . '\');' . "\n";
	$output .= 'define(\'DB_USERNAME\', \'' . addslashes($options['db_user']) . '\');' . "\n";
	$output .= 'define(\'DB_PASSWORD\', \'' . addslashes($options['db_password']) . '\');' . "\n";
	$output .= 'define(\'DB_DATABASE\', \'' . addslashes($options['db_name']) . '\');' . "\n";
	$output .= 'define(\'DB_PREFIX\', \'' . addslashes($options['db_prefix']) . '\');' . "\n";
	$output .= '?>';

	$file = fopen(DIR_OPENCART . 'config.php', 'w');

	fwrite($file, $output);

	fclose($file);

	$output  = '<?php' . "\n";
	$output .= '// HTTP' . "\n";
	$output .= 'define(\'HTTP_SERVER\', \'' . $options['http_server'] . 'admin/\');' . "\n";
	$output .= 'define(\'HTTP_CATALOG\', \'' . $options['http_server'] . '\');' . "\n";
	$output .= 'define(\'HTTP_IMAGE\', \'' . $options['http_server'] . 'image/\');' . "\n\n";

	$output .= '// HTTPS' . "\n";
	$output .= 'define(\'HTTPS_SERVER\', \'' . $options['http_server'] . 'admin/\');' . "\n";
	$output .= 'define(\'HTTPS_CATALOG\', \'' . $options['http_server'] . '\');' . "\n";
	$output .= 'define(\'HTTPS_IMAGE\', \'' . $options['http_server'] . 'image/\');' . "\n\n";

	$output .= '// DIR' . "\n";
	$output .= 'define(\'DIR_APPLICATION\', \'' . DIR_OPENCART . 'admin/\');' . "\n";
	$output .= 'define(\'DIR_SYSTEM\', \'' . DIR_OPENCART . 'system/\');' . "\n";
	$output .= 'define(\'DIR_DATABASE\', \'' . DIR_OPENCART . 'system/database/\');' . "\n";
	$output .= 'define(\'DIR_LANGUAGE\', \'' . DIR_OPENCART . 'admin/language/\');' . "\n";
	$output .= 'define(\'DIR_TEMPLATE\', \'' . DIR_OPENCART . 'admin/view/template/\');' . "\n";
	$output .= 'define(\'DIR_CONFIG\', \'' . DIR_OPENCART . 'system/config/\');' . "\n";
	$output .= 'define(\'DIR_IMAGE\', \'' . DIR_OPENCART . 'image/\');' . "\n";
	$output .= 'define(\'DIR_CACHE\', \'' . DIR_OPENCART . 'system/cache/\');' . "\n";
	$output .= 'define(\'DIR_DOWNLOAD\', \'' . DIR_OPENCART . 'download/\');' . "\n";
	$output .= 'define(\'DIR_LOGS\', \'' . DIR_OPENCART . 'system/logs/\');' . "\n";
	$output .= 'define(\'DIR_CATALOG\', \'' . DIR_OPENCART . 'catalog/\');' . "\n\n";

	$output .= '// DB' . "\n";
	$output .= 'define(\'DB_DRIVER\', \'mysql\');' . "\n";
	$output .= 'define(\'DB_HOSTNAME\', \'' . addslashes($options['db_host']) . '\');' . "\n";
	$output .= 'define(\'DB_USERNAME\', \'' . addslashes($options['db_user']) . '\');' . "\n";
	$output .= 'define(\'DB_PASSWORD\', \'' . addslashes($options['db_password']) . '\');' . "\n";
	$output .= 'define(\'DB_DATABASE\', \'' . addslashes($options['db_name']) . '\');' . "\n";
	$output .= 'define(\'DB_PREFIX\', \'' . addslashes($options['db_prefix']) . '\');' . "\n";
	$output .= '?>';

	$file = fopen(DIR_OPENCART . 'admin/config.php', 'w');

	fwrite($file, $output);

	fclose($file);
}


function dir_permissions() {
	$dirs = array(
		DIR_OPENCART . 'image/',
		DIR_OPENCART . 'download/',
		DIR_SYSTEM . 'cache/',
		DIR_SYSTEM . 'logs/',
	);
	exec('chmod o+w -R ' . implode(' ', $dirs));
}


$argv = $_SERVER['argv'];
$script = array_shift($argv);
$subcommand = array_shift($argv);


switch ($subcommand) {

case "install":
	try {
		$options = get_options($argv);
		define('HTTP_OPENCART', $options['http_server']);
		$valid = valid($options);
		if (!$valid[0]) {
			echo "FAILED! Following inputs were missing or invalid: ";
			echo implode(', ',  $valid[1]) . "\n\n";
			exit(1);
		}
		install($options);
		echo "SUCCESS! Opencart successfully installed on your server\n";
		echo "Store link: " . $options['http_server'] . "\n";
		echo "Admin link: " . $options['http_server'] . "admin/\n\n";
	} catch (ErrorException $e) {
		echo 'FAILED!: ' . $e->getMessage() . "\n";
		exit(1);
	}
	break;
case "usage":
default:
	echo usage();
}
?>
