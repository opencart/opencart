<?php
// Version
define('VERSION', '2.2.0.0_rc');

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
$registry = new Registry();

// Config
$config = new Config();
$registry->set('config', $config);

// Database
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
$registry->set('db', $db);

// Settings
$query = $db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '0'");

foreach ($query->rows as $setting) {
	if (!$setting['serialized']) {
		$config->set($setting['key'], $setting['value']);
	} else {
		$config->set($setting['key'], json_decode($setting['value'], true));
	}
}

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

// Url
$url = new Url(HTTP_SERVER, $config->get('config_secure') ? HTTPS_SERVER : HTTP_SERVER);
$registry->set('url', $url);

// Log
$registry->set('log', new Log($config->get('config_error_filename')));

// Request
$request = new Request();
$registry->set('request', $request);

// Response
$response = new Response();
$response->addHeader('Content-Type: text/html; charset=utf-8');
$registry->set('response', $response);

// Cache
$cache = new Cache('file');
$registry->set('cache', $cache);

// Session
$session = new Session();
$session->start();
$registry->set('session', $session);

// Language
$query = $db->query("SELECT * FROM `" . DB_PREFIX . "language` WHERE code = '" . $db->escape($config->get('config_admin_language')) . "'");

if ($query->num_rows) {
	$config->set('config_language_id', $query->row['language_id']);
} else {
	exit();
}

// Language
$language = new Language($config->get('config_admin_language'));
$language->load($config->get('config_admin_language'));
$registry->set('language', $language);

// Document
$registry->set('document', new Document());

// Currency
$registry->set('currency', new Cart\Currency($registry));

// Weight
$registry->set('weight', new Cart\Weight($registry));

// Length
$registry->set('length', new Cart\Length($registry));

// User
$registry->set('user', new Cart\User($registry));

// OpenBay Pro
$registry->set('openbay', new Openbay($registry));

// Event
$event = new Event($registry);
$registry->set('event', $event);

$query = $db->query("SELECT * FROM `" . DB_PREFIX . "event` WHERE `trigger` LIKE 'admin/%'");

foreach ($query->rows as $result) {
	$event->register(substr($result['trigger'], strrpos($result['trigger'], '/') + 1), new Action($result['action']));
}

// Front Controller
$controller = new Front($registry);

// Error Handling
$controller->addPreAction(new Action('event/error'));

// Compile Sass
$controller->addPreAction(new Action('event/sass'));

// Login
$controller->addPreAction(new Action('event/login'));

// Permission
$controller->addPreAction(new Action('event/permission'));

// Dispatch
$controller->dispatch(new Action('event/route'), new Action('error/not_found'));

// Output
$response->output();