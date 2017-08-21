<?php
// Error Reporting
error_reporting(E_ALL);

// Check if SSL
if ((isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) || $_SERVER['SERVER_PORT'] == 443) {
	$protocol = 'https://';
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
	$protocol = 'https://';
} else {
	$protocol = 'http://';
}

define('HTTP_SERVER', $protocol . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/.\\') . '/');
define('HTTP_OPENCART', $protocol . $_SERVER['HTTP_HOST'] . rtrim(rtrim(dirname($_SERVER['SCRIPT_NAME']), 'install'), '/.\\') . '/');

// DIR
define('DIR_OPENCART', str_replace('\\', '/', realpath(dirname(__FILE__) . '/../') . '/'));
define('DIR_APPLICATION', str_replace('\\', '/', realpath(dirname(__FILE__))) . '/');
define('DIR_SYSTEM', str_replace('\\', '/', realpath(dirname(__FILE__) . '/../')) . '/system/');
define('DIR_STORAGE', DIR_SYSTEM . 'storage/');
define('DIR_IMAGE', str_replace('\\', '/', realpath(dirname(__FILE__) . '/../')) . '/image/');
define('DIR_LANGUAGE', DIR_APPLICATION . 'language/');
define('DIR_TEMPLATE', DIR_APPLICATION . 'view/template/');
define('DIR_DATABASE', DIR_SYSTEM . 'database/');
define('DIR_CONFIG', DIR_SYSTEM . 'config/');
define('DIR_CACHE', DIR_SYSTEM . 'storage/cache/');
define('DIR_LOGS', DIR_SYSTEM . 'storage/logs/');
define('DIR_MODIFICATION', DIR_SYSTEM . 'storage/modification/');
define('DIR_DOWNLOAD', DIR_SYSTEM . 'storage/download/');
define('DIR_SESSION', DIR_SYSTEM . 'storage/session/');
define('DIR_UPLOAD', DIR_SYSTEM . 'storage/upload/');

// Startup
require_once(DIR_SYSTEM . 'startup.php');

start('install');
