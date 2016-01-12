<?php
// Error Reporting
error_reporting(E_ALL);

// HTTP
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";


// Rewrite HTTPS index based on proxy
$ssl_pool = array(!empty($_SERVER['HTTPS']), !empty($_SERVER['HTTP_X_FORWARDED_PROTO']), !empty($_SERVER['HTTP_X_FORWARDED_PROTOCOL']), !empty($_SERVER['HTTP_X_FORWARDED_SSL']), !empty($_SERVER['HTTP_FRONT_END_HTTPS']), !empty($_SERVER['HTTP_X_URL_SCHEME']), !empty($_SERVER['SERVER_PORT']));

define('HTTP_SERVER', $_SERVER['PROTOCOL'] . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/.\\') . '/');
define('HTTP_OPENCART', $_SERVER['PROTOCOL'] . $_SERVER['HTTP_HOST'] . rtrim(rtrim(dirname($_SERVER['SCRIPT_NAME']), 'install'), '/.\\') . '/');

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
require_once(DIR_SYSTEM . 'framework.php');
