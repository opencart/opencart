<?php
// Version
define('VERSION', '2.0.0.0a1');

// Configuration
if (is_file('config.php')) {
	require_once('config.php');
}

// Install
if (!defined('DIR_APPLICATION')) {
	header('Location: ../install/index.php');
	exit;
}

// Startup
require_once(DIR_SYSTEM . 'startup.php');

// Registry
$registry = new Engine\Registry();

// Config
$config = new Library\Config();
$registry->set('config', $config);

// Database
$db = new Library\DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
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
$loader = new Engine\Loader($registry);
$registry->set('load', $loader);

// Url
$url = new Library\Url(HTTP_SERVER, $config->get('config_secure') ? HTTPS_SERVER : HTTP_SERVER);
$registry->set('url', $url);

// Log
$log = new Library\Log($config->get('config_error_filename'));
$registry->set('log', $log);

function error_handler($errno, $errstr, $errfile, $errline) {
	global $log, $config;

	// error suppressed with @
	if (error_reporting() === 0) {
		return false;
	}

	switch ($errno) {
		case E_NOTICE:
		case E_USER_NOTICE:
			$error = 'Notice';
			break;
		case E_WARNING:
		case E_USER_WARNING:
			$error = 'Warning';
			break;
		case E_ERROR:
		case E_USER_ERROR:
			$error = 'Fatal Error';
			break;
		default:
			$error = 'Unknown';
			break;
	}

	if ($config->get('config_error_display')) {
		echo '<b>' . $error . '</b>: ' . $errstr . ' in <b>' . $errfile . '</b> on line <b>' . $errline . '</b>';
	}

	if ($config->get('config_error_log')) {
		$log->write('PHP ' . $error . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
	}

	return true;
}

// Error Handler
set_error_handler('error_handler');

// Request
$request = new Library\Request();
$registry->set('request', $request);

// Response
$response = new Library\Response();
$response->addHeader('Content-Type: text/html; charset=utf-8');
$registry->set('response', $response);

// Cache
$cache = new Library\Cache('file');
$registry->set('cache', $cache);

// Session
$session = new Library\Session();
$registry->set('session', $session);

// Language
$languages = array();

$query = $db->query("SELECT * FROM `" . DB_PREFIX . "language`");

foreach ($query->rows as $result) {
	$languages[$result['code']] = $result;
}

$config->set('config_language_id', $languages[$config->get('config_admin_language')]['language_id']);

// Language
$language = new Library\Language($languages[$config->get('config_admin_language')]['directory']);
$language->load($languages[$config->get('config_admin_language')]['filename']);
$registry->set('language', $language);

// Document
$registry->set('document', new Library\Document());

// Currency
$registry->set('currency', new Library\Currency($registry));

// Weight
$registry->set('weight', new Library\Weight($registry));

// Length
$registry->set('length', new Library\Length($registry));

// User
$registry->set('user', new Library\User($registry));

// Event
$registry->set('event', new Library\Event($registry));

// Front Controller
$controller = new Engine\Front($registry);

// Login
$controller->addPreAction(new Engine\Action('common/login/check'));

// Permission
$controller->addPreAction(new Engine\Action('error/permission/check'));

// Router
if (isset($request->get['route'])) {
	$action = new Engine\Action($request->get['route']);
} else {
	$action = new Engine\Action('common/dashboard');
}

// Dispatch
$controller->dispatch($action, new Engine\Action('error/not_found'));

// Output
$response->output();