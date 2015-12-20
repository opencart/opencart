<?php
// Version
define('VERSION', '2.2.0.0_rc');

// Configuration
if (is_file('config.php')) {
	require_once('config.php');
}

// Install
if (!defined('DIR_APPLICATION')) {
	header('Location: install/index.php');
	exit;
}

// Startup
require_once(DIR_SYSTEM . 'startup.php');

// Registry
$registry = new Registry();

// Loader
$registry->set('load', new Loader($registry));

// Config
$config = new Config();

// Load config defaults
$config->load('default');
$config->load('catalog');
// Get additional config settings setup by the default config file
//foreach ($config->get('') as $file) {
//	$config->load('catalog');
//}

$registry->set('config', $config);

// Request
$registry->set('request', new Request());

// Response
$response = new Response();
$response->addHeader('Content-Type: text/html; charset=utf-8');
$response->setCompression($config->get('config_compression'));
$registry->set('response', $response);

// Session
$registry->set('session', new Session());

// Cache
$registry->set('cache', new Cache($config->get('config_cache')));

// Url
$registry->set('url', new Url($config->get('config_url')));

// Event
$registry->set('event', new Event($registry));

// Language
$registry->set('language', new Language('en-gb'));

// Document
$registry->set('document', new Document());

// Front Controller
$controller = new Front($registry);

// Pre Action
if ($config->has('config_pre_action')) {
	foreach ($config->get('config_pre_action') as $action) {
		$controller->addPreAction(new Action($action));
	}
}

// Dispatch
$controller->dispatch(new Action('action/route'), new Action('error/not_found'));

// Output
$response->output();