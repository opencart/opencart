<?php
// Error Reporting
error_reporting(E_ALL);

// Check Version
if (version_compare(phpversion(), '7.3.0', '<')) {
	exit('PHP7.3+ Required');
}

if (!ini_get('date.timezone')) {
	date_default_timezone_set('UTC');
}

// Windows IIS Compatibility
if (!isset($_SERVER['DOCUMENT_ROOT'])) {
	if (isset($_SERVER['SCRIPT_FILENAME'])) {
		$_SERVER['DOCUMENT_ROOT'] = str_replace('\\', '/', substr($_SERVER['SCRIPT_FILENAME'], 0, 0 - strlen($_SERVER['PHP_SELF'])));
	}
}

if (!isset($_SERVER['DOCUMENT_ROOT'])) {
	if (isset($_SERVER['PATH_TRANSLATED'])) {
		$_SERVER['DOCUMENT_ROOT'] = str_replace('\\', '/', substr(str_replace('\\\\', '\\', $_SERVER['PATH_TRANSLATED']), 0, 0 - strlen($_SERVER['PHP_SELF'])));
	}
}

if (!isset($_SERVER['REQUEST_URI'])) {
	$_SERVER['REQUEST_URI'] = substr($_SERVER['PHP_SELF'], 1);

	if (isset($_SERVER['QUERY_STRING'])) {
		$_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
	}
}

if (!isset($_SERVER['HTTP_HOST'])) {
	$_SERVER['HTTP_HOST'] = getenv('HTTP_HOST');
}

// Check if SSL
if ((isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) || (isset($_SERVER['HTTPS']) && (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443))) {
	$_SERVER['HTTPS'] = true;
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
	$_SERVER['HTTPS'] = true;
} else {
	$_SERVER['HTTPS'] = false;
}

// Engine
require_once(DIR_SYSTEM . 'engine/controller.php');
require_once(DIR_SYSTEM . 'engine/model.php');
require_once(DIR_SYSTEM . 'engine/action.php');
require_once(DIR_SYSTEM . 'engine/event.php');
require_once(DIR_SYSTEM . 'engine/loader.php');
require_once(DIR_SYSTEM . 'engine/registry.php');
require_once(DIR_SYSTEM . 'engine/proxy.php');

// Helper
require_once(DIR_SYSTEM . 'helper/general.php');
require_once(DIR_SYSTEM . 'helper/utf8.php');

// Vendor Autoloader
require_once(DIR_STORAGE . 'vendor/autoload.php');

// Library Autoloader
function autoloader($class) {
	$file = '';

	// Converting a class name to a file path
	$path = strtolower(implode('/', preg_replace('~([a-z])([A-Z])~', '\\1_\\2', explode('\\', $class))));

	$type = substr($path, 0, strpos($path, '/'));

	$path = substr($path, strpos($path, '/') + 1);

	switch ($type) {
		case 'application':
			$file = DIR_APPLICATION .  $path  . '.php';
			break;
		case 'catalog':
			$file = DIR_CATALOG . $path  . '.php';
			break;
		case 'admin':
			$file = DIR_ADMIN . $path . '.php';
			break;
		case 'extension':
			$file = DIR_EXTENSION . $path  . '.php';
			break;
		case 'system':
			$file = DIR_SYSTEM . $path . '.php';
			break;
	}

	//echo 'autoloader' . "\n";
	//echo '$class ' . $class . "\n";
	//echo '$path ' . $path . "\n";

	if (is_file($file)) {
		include_once($file);

		//echo '$file ' . $file . "\n\n";

		return true;
	} else {
		//echo 'Not found: ' . $file . "\n\n";

		return false;
	}
}

spl_autoload_register('autoloader');
spl_autoload_extensions('.php');

function start($application) {
	require_once(DIR_SYSTEM . 'framework.php');	
}