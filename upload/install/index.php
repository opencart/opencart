<?php
// Error Reporting
error_reporting(E_ALL);

// Rewrite HTTPS index based on proxy
$ssl_pool = array(
	!empty($_SERVER['HTTPS']),
	!empty($_SERVER['HTTP_X_FORWARDED_PROTO']),
	!empty($_SERVER['HTTP_X_FORWARDED_PROTOCOL']),
	!empty($_SERVER['HTTP_X_FORWARDED_SSL']),
	!empty($_SERVER['HTTP_FRONT_END_HTTPS']),
	!empty($_SERVER['HTTP_X_URL_SCHEME']),
	!empty($_SERVER['SERVER_PORT'])
);

oc_route_ssl_inst($ssl_pool);

function oc_route_ssl_inst($ssl_pool) {
	$_SERVER['HTTPS'] = false;
	$_SERVER['PROTOCOL'] = 'http://';
	
	foreach ($ssl_pool as $ssl) {
		if (isset($ssl) && ($ssl == 'https' || $ssl == 'on' || $ssl == 1 || $ssl == true || $ssl == 443)) {
			$_SERVER['HTTPS'] = true;
			$_SERVER['PROTOCOL'] = 'https://';
			break;
		}
	}
}

define('HTTP_SERVER', $_SERVER['PROTOCOL'] . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/.\\') . '/');
define('HTTP_OPENCART', 'http://' . $_SERVER['HTTP_HOST'] . rtrim(rtrim(dirname($_SERVER['SCRIPT_NAME']), 'install'), '/.\\') . '/');
define('HTTPS_OPENCART', $_SERVER['PROTOCOL'] . $_SERVER['HTTP_HOST'] . rtrim(rtrim(dirname($_SERVER['SCRIPT_NAME']), 'install'), '/.\\') . '/');

// DIR
define('DIR_APPLICATION', str_replace('\\', '/', realpath(dirname(__FILE__))) . '/');
define('DIR_SYSTEM', str_replace('\\', '/', realpath(dirname(__FILE__) . '/../')) . '/system/');
define('DIR_OPENCART', str_replace('\\', '/', realpath(DIR_APPLICATION . '../')) . '/');
define('DIR_MODIFICATION', DIR_SYSTEM . 'storage/modification/');
define('DIR_LANGUAGE', DIR_APPLICATION . 'language/');
define('DIR_TEMPLATE', DIR_APPLICATION . 'view/template/');
define('DIR_CONFIG', DIR_SYSTEM . 'config/');
define('DIR_CACHE', DIR_SYSTEM . 'storage/cache/');

// Startup
require_once(DIR_SYSTEM . 'startup.php');

$application_config = 'install';

// Application
require_once(DIR_SYSTEM . 'application.php');
