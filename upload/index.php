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
require_once(DIR_SYSTEM . 'library/customer.php');
require_once(DIR_SYSTEM . 'library/currency.php');
require_once(DIR_SYSTEM . 'library/tax.php');
require_once(DIR_SYSTEM . 'library/weight.php');
require_once(DIR_SYSTEM . 'library/cart.php');

// Page Time
$time = (time() + microtime());

// Loader
$loader = new Loader();
Registry::set('load', $loader);

// Config
$config = new Config();
Registry::set('config', $config);

// Database 
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PREFIX);
Registry::set('db', $db);

// Settings
$query = $db->query("SELECT * FROM setting");

foreach ($query->rows as $setting) {
	$config->set($setting['key'], $setting['value']);
}

// Request
$request = new Request();
Registry::set('request', $request);

// Response
$response = new Response();
$response->addHeader('Content-Type', 'text/html; charset=UTF-8');
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
Registry::set('customer', new Customer());

// Currency
Registry::set('currency', new Currency());

// Tax
Registry::set('tax', new Tax());

// Weight
Registry::set('weight', new Weight());

// Cart
Registry::set('cart', new Cart());

// Front Controller 
$controller = new Front();

// Router
if (isset($request->get['route'])) {
	$action = new Router($request->get['route']);
} else {
	$action = new Action('common/home', 'index');
}

// Dispatch
$controller->dispatch($action, new Action('error/not_found', 'index'));

// Output
$response->output();

// Parse Time
if ($config->get('config_parse_time')) {
	echo sprintf($language->get('text_time'), round((time() + microtime()) - $time, 4));
}
?>