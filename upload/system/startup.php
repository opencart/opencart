<?php
// Error Reporting
error_reporting(E_ALL);

// Check Version
if (version_compare(phpversion(), '5.3.0', '<') == true) {
	exit('PHP5.3+ Required');
}

// Register Globals
if (ini_get('register_globals')) {
	ini_set('session.use_cookies', 'On');
	ini_set('session.use_trans_sid', 'Off');
	
	if (!isset($_COOKIE[session_name()]) || !preg_match('/^[a-z0-9]{32}$/', $_COOKIE[session_name()])) {
		session_set_cookie_params(0, '/');
		session_start();
	}
	
	$globals = array($_REQUEST, $_SESSION, $_SERVER, $_FILES);

	foreach ($globals as $global) {
		foreach(array_keys($global) as $key) {
			unset(${$key}); 
		}
	}
}

// Magic Quotes Fix
if (ini_get('magic_quotes_gpc')) {
	function clean($data) {
		if (is_array($data)) {
			foreach ($data as $key => $value) {
				$data[clean($key)] = clean($value);
			}
		} else {
			$data = stripslashes($data);
		}
	
		return $data;
	}			
	
	$_GET = clean($_GET);
	$_POST = clean($_POST);
	$_REQUEST = clean($_REQUEST);
	$_COOKIE = clean($_COOKIE);
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

// Engine
require_once(DIR_SYSTEM . 'engine/action.php'); 
require_once(DIR_SYSTEM . 'engine/controller.php');
require_once(DIR_SYSTEM . 'engine/front.php');
require_once(DIR_SYSTEM . 'engine/loader.php'); 
require_once(DIR_SYSTEM . 'engine/model.php');
require_once(DIR_SYSTEM . 'engine/registry.php');

// Autoloader
function __autoload($class) {
	if (substr($class, 0, 10) == 'Controller' || substr($class, 0, 5) == 'Model') {
		$file = DIR_APPLICATION . str_replace('\\', '/', strtolower($class)) . '.php';
	} else {
		$file = DIR_SYSTEM . 'library/' . strtolower($class) . '.php';
	}
	
	if (file_exists($file)) {
		include($file);
	} else {
		trigger_error('Error: Could not load class ' . $class . '.php!');
		exit();
	}
}

// Helper
require_once(DIR_SYSTEM . 'helper/json.php');
require_once(DIR_SYSTEM . 'helper/utf8.php');