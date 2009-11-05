<?php
// Configuration
require_once('config.php');

// Startup
require_once(DIR_SYSTEM . 'startup.php');

// Load the application classes
require_once(DIR_SYSTEM . 'library/currency.php');
require_once(DIR_SYSTEM . 'library/user.php');
require_once(DIR_SYSTEM . 'library/weight.php');
require_once(DIR_SYSTEM . 'library/measurement.php');

// Loader
$loader = new Loader();
Registry::set('load', $loader);

// Config
$config = new Config();
Registry::set('config', $config);

// Database
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
Registry::set('db', $db);

// Settings
$query = $db->query("SELECT * FROM " . DB_PREFIX . "setting");
 
foreach ($query->rows as $setting) {
	$config->set($setting['key'], $setting['value']);
}

$log = new Logger($config->get('config_error_filename'));
Registry::set('log', $log);

// Error Handler
function error_handler($errno, $errstr, $errfile, $errline) {
	global $config, $log;
	
	switch ($errno) {
		case E_NOTICE:
		case E_USER_NOTICE:
			$error = "Notice";
			break;
		case E_WARNING:
		case E_USER_WARNING:
			$error = "Warning";
			break;
		case E_ERROR:
		case E_USER_ERROR:
			$error = "Fatal Error";
			break;
		default:
			$error = "Unknown";
			break;
	}
		
    if ($config->get('config_error_display')) {
        echo '<b>' . $error . '</b>: ' . $errstr . ' in <b>' . $errfile . '</b> on line <b>' . $errline . '</b>';
	}
	
	if ($config->get('config_error_log')) {
		$log->write('PHP ' . $error . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
	}

	return TRUE;
}

// set to the user defined error handler
set_error_handler('error_handler');

// Request
$request = new Request();
Registry::set('request', $request);

// Response
$response = new Response();
$response->addHeader('Content-Type', 'text/html; charset=utf-8');
Registry::set('response', $response);

// Session
Registry::set('session', new Session());

// Cache
Registry::set('cache', new Cache());

// Url
Registry::set('url', new Url());

// Document
Registry::set('document', new Document());

// Language
$language = new Language($config->get('config_admin_language'));
Registry::set('language', $language);

// Currency
Registry::set('currency', new Currency());

// Weight
Registry::set('weight', new Weight());

// Measurement
Registry::set('measurement', new Measurement());

// User
Registry::set('user', new User());

// Front Controller
$controller = new Front();

// Login
$controller->addPreAction(new Router('common/login/check'));

// Permission
$controller->addPreAction(new Router('common/permission/check'));

// Router
if (isset($request->get['route'])) {
	$action = new Router($request->get['route']);
} else {
	$action = new Router('common/home');
}

// Dispatch
$controller->dispatch($action, new Router('error/not_found'));

// Output
$response->output($config->get('config_compression'));
?>