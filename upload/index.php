<?php
// Configuration
require_once('config.php');
   
// Install 
if (!defined('HTTP_SERVER')) {
	header('Location: install/index.php');
	exit;
} 

// Startup
require_once(DIR_SYSTEM . 'startup.php');

// Load the application classes
require_once(DIR_SYSTEM . 'helper/customer.php');
require_once(DIR_SYSTEM . 'helper/currency.php');
require_once(DIR_SYSTEM . 'helper/tax.php');
require_once(DIR_SYSTEM . 'helper/weight.php');
require_once(DIR_SYSTEM . 'helper/measurement.php');
require_once(DIR_SYSTEM . 'helper/cart.php');

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
			$errors = "Notice";
			break;
		case E_WARNING:
		case E_USER_WARNING:
			$errors = "Warning";
			break;
		case E_ERROR:
		case E_USER_ERROR:
			$errors = "Fatal Error";
			break;
		default:
			$errors = "Unknown";
			break;
	}
		
    if ($config->get('config_error_display')) {
        echo '<b>' . $errors . '</b>: ' . $errstr . ' in <b>' . $errfile . '</b> on line <b>' . $errline . '</b>';
	}
	
	if ($config->get('config_error_log')) {
		$log->write('PHP ' . $errors . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
	}

	return true;
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

// Cache
Registry::set('cache', new Cache());

// Url
Registry::set('url', new Url());

// Session
$session = new Session();
Registry::set('session', $session);

// Language		
$language = new Language();
Registry::set('language', $language);
	
// Document
Registry::set('document', new Document());

// Customer
Registry::set('customer', new HelperCustomer());

// Currency
Registry::set('currency', new HelperCurrency());

// Tax
Registry::set('tax', new HelperTax());

// Weight
Registry::set('weight', new HelperWeight());

// Weight
Registry::set('measurement', new HelperMeasurement());

// Cart
Registry::set('cart', new HelperCart());

// Front Controller 
$controller = new Front();

// SEO URL's
$controller->addPreAction(new Router('common/seo_url'));

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