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
$config->load('default');
$config->load('catalog');
$registry->set('config', $config);

// Log
//$registry->set('log', new Log($config->get('config_error_filename')));

// Request
$registry->set('request', new Request());

// Response
$response = new Response();
$response->addHeader('Content-Type: text/html; charset=utf-8');
$response->setCompression($config->get('config_compression'));
$registry->set('response', $response);

// Session
$registry->set('session', new Session($config->get('config_session')));

// Cache
$registry->set('cache', new Cache($config->get('config_cache')));

// Url
$registry->set('url', new Url($config->get('config_url'), $config->get('config_secure') ? $config->get('config_ssl') : $config->get('config_url')));

// Event
$registry->set('event', new Event($registry));

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