<?php
// Error Reporting
error_reporting(E_ALL);

// Check Version
if (version_compare(phpversion(), '5.3.0', '<') == true) {
	exit('PHP5.3+ Required');
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
if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
	$_SERVER['HTTPS'] = true;
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
	$_SERVER['HTTPS'] = true;
} else {
	$_SERVER['HTTPS'] = false;
}

// Modification Override
function modification($filename) {
	if (!defined('DIR_CATALOG')) {
		$file = DIR_MODIFICATION . 'catalog_' . str_replace('/', '_', substr($filename, strlen(DIR_APPLICATION)));
	} else {
		$file = DIR_MODIFICATION . 'admin_' .  str_replace('/', '_', substr($filename, strlen(DIR_APPLICATION)));
	}

	if (substr($filename, 0, strlen(DIR_SYSTEM)) == DIR_SYSTEM) {
		$file = DIR_MODIFICATION . 'system_' . str_replace('/', '_', substr($filename, strlen(DIR_SYSTEM)));
	}

	if (file_exists($file)) {
		return $file;
	} else {
		return $filename;
	}
}

// Autoloader
function autoload($class) {
	if (substr($class, 0, 10) == 'Controller') {
		$file = DIR_APPLICATION . str_replace('\\', '/', strtolower($class)) . '.php';
	}
	
	if (substr($class, 0, 5) == 'Model') {
		$file = DIR_APPLICATION . str_replace('\\', '/', strtolower($class)) . '.php';
	}
	
	if (substr($class, 0, 7) == 'Library') {
		$file = DIR_SYSTEM . str_replace('\\', '/', strtolower($class)) . '.php';
	}
	
	if (substr($class, 0, 6) == 'Engine') {
		$file = DIR_SYSTEM . str_replace('\\', '/', strtolower($class)) . '.php';
	}
	
	echo $class . '<br>';
			
	if (file_exists($file)) {
		include(modification($file));
		
		return true;
	} else {
		return false;
	}
}

spl_autoload_register('autoload');
spl_autoload_extensions('.php');

// Helper
require_once(DIR_SYSTEM . 'helper/json.php');
require_once(DIR_SYSTEM . 'helper/utf8.php');