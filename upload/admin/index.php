<?php
// Version
define('VERSION', '2.0');

// Configuration
if (is_file('config.php')) {
	require_once('config.php');
}  

// Install
if (!defined('DIR_APPLICATION')) {
	header('Location: ../install/index.php');
	exit;
}

// Modification
require_once(DIR_SYSTEM . 'engine/modification.php');
$modification = new Modification();

// Startup
require_once($modification->getFile(DIR_SYSTEM . 'startup.php'));

// Application
require_once($modification->getFile(DIR_SYSTEM . 'library/currency.php'));
require_once($modification->getFile(DIR_SYSTEM . 'library/user.php'));
require_once($modification->getFile(DIR_SYSTEM . 'library/weight.php'));
require_once($modification->getFile(DIR_SYSTEM . 'library/length.php'));

// Registry
$registry = new Registry();

// Modification
$registry->set('modification', $modification);

// Config
$config = new Config();
$registry->set('config', $config);

// Database
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$registry->set('db', $db);
		
// Settings
$query = $db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '0'");
 
foreach ($query->rows as $setting) {
	if (!$setting['serialized']) {
		$config->set($setting['key'], $setting['value']);
	} else {
		$config->set($setting['key'], unserialize($setting['value']));
	}
}

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

// Url
$url = new Url(HTTP_SERVER, $config->get('config_secure') ? HTTPS_SERVER : HTTP_SERVER);	
$registry->set('url', $url);

// Log 
$log = new Log($config->get('config_error_filename'));
$registry->set('log', $log);

// Error Handler
set_error_handler(function($number, $string, $file, $line) {
	throw new ErrorException($string, $number, 0, $file, $line);
});
		
// Request
$request = new Request();
$registry->set('request', $request);

// Response
$response = new Response();
$response->addHeader('Content-Type: text/html; charset=utf-8');
$registry->set('response', $response); 

// Cache
$cache = new Cache();
$registry->set('cache', $cache); 

// Session
$session = new Session();
$registry->set('session', $session); 

// Language
$languages = array();

$query = $db->query("SELECT * FROM `" . DB_PREFIX . "language`"); 

foreach ($query->rows as $result) {
	$languages[$result['code']] = $result;
}

$config->set('config_language_id', $languages[$config->get('config_admin_language')]['language_id']);

// Language	
$language = new Language($languages[$config->get('config_admin_language')]['directory']);
$language->load($languages[$config->get('config_admin_language')]['filename']);	
$registry->set('language', $language);

// Document
$registry->set('document', new Document()); 		
		
// Currency
$registry->set('currency', new Currency($registry));		
		
// Weight
$registry->set('weight', new Weight($registry));

// Length
$registry->set('length', new Length($registry));

// User
$registry->set('user', new User($registry));

// Front Controller
$controller = new Front($registry);

// Login
$controller->addPreAction(new Action('common/login/check'));

// Permission
$controller->addPreAction(new Action('error/permission/check'));

// Router
if (isset($request->get['route'])) {
	$action = new Action($request->get['route']);
} else {
	$action = new Action('common/home');
}

try {
	// Dispatch
	$controller->dispatch($action, new Action('error/not_found'));
} catch(Exception $exception) {
	// Catch any errors and log them!
	if ($config->get('config_error_display')) {
		echo sprintf($language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
	}
	
	if ($config->get('config_error_log')) {
		$log->write(sprintf($language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine()));
	}
}

// Output
$response->output();
?>