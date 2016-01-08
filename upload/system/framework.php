<?php
// Registry
$registry = new Registry();

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

// Config
$config = new Config();
$config->load('default');
$config->load($application_config);
$registry->set('config', $config);

// Request
$registry->set('request', new Request());

// Response
$response = new Response();
$response->addHeader('Content-Type: text/html; charset=utf-8');
$registry->set('response', $response);

// Database
if ($config->get('db.autostart')) {
	$registry->set('db', new DB($config->get('db.type'), $config->get('db.hostname'), $config->get('db.username'), $config->get('db.password'), $config->get('db.database'), $config->get('db.port')));
}

// Session
if ($config->get('session.autostart')) {
	$session = new Session();
	$session->start();
	$registry->set('session', $session);
}

// Cache 
$registry->set('cache', new Cache($config->get('cache.type'), $config->get('cache.expire')));

// Url
$registry->set('url', new Url($config->get('site.base'), $config->get('site.ssl')));

// Language
$language = new Language($config->get('language.default'));
$language->load($config->get('language.default'));
$registry->set('language', $language);

// Log
//$registry->set('log', new Log($config->get('error.filename')));

// Document
$registry->set('document', new Document());

// Event
$event = new Event($registry);
$registry->set('event', $event);

// Event Register
if ($config->has('action.event')) {
	foreach ($config->get('action.event') as $key => $value) {
		$event->register($key, new Action($value));
	}
}

// Config Autoload
if ($config->has('config.autoload')) {
	foreach ($config->get('config.autoload') as $value) {
		$loader->config($value);
	}
}

// Language Autoload
if ($config->has('language.autoload')) {
	foreach ($config->get('language.autoload') as $value) {
		$loader->language($value);
	}
}

// Library Autoload
if ($config->has('library.autoload')) {
	foreach ($config->get('library.autoload') as $value) {
		$loader->library($value);
	}
}

// Model Autoload
if ($config->has('model.autoload')) {
	foreach ($config->get('model.autoload') as $value) {
		$loader->model($value);
	}
}

// Front Controller
$controller = new Front($registry);

// Pre Actions
if ($config->has('action.pre_action')) {
	foreach ($config->get('action.pre_action') as $value) {
		$controller->addPreAction(new Action($value));
	}
}

// Dispatch
$controller->dispatch(new Action($config->get('action.router')), new Action($config->get('action.error')));

// Output
$response->setCompression($config->get('config_compression'));
$response->output();