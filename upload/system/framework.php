<?php
// Autoloader
$autoloader = new \Opencart\System\Engine\Autoloader();
$autoloader->register('Opencart\Application', DIR_APPLICATION);
$autoloader->register('Opencart\Extension', DIR_EXTENSION);
$autoloader->register('Opencart\System', DIR_SYSTEM);

// Registry
$registry = new \Opencart\System\Engine\Registry();
$registry->set('autoloader', $autoloader);

// Config
$config = new \Opencart\System\Engine\Config();
$config->addPath(DIR_CONFIG);

// Load the default config
$config->load('default');
$config->load(basename(DIR_APPLICATION));
$registry->set('config', $config);

// Set the default time zone
date_default_timezone_set($config->get('date_timezone'));

// Event
$event = new \Opencart\System\Engine\Event($registry);
$registry->set('event', $event);

// Event Register
if ($config->has('action_event')) {
	foreach ($config->get('action_event') as $key => $value) {
		foreach ($value as $priority => $action) {
			$event->register($key, new \Opencart\System\Engine\Action($action), $priority);
		}
	}
}

// Loader
$loader = new \Opencart\System\Engine\Loader($registry);
$registry->set('load', $loader);

// Request
$request = new \Opencart\System\Library\Request();
$registry->set('request', $request);

// Response
$response = new \Opencart\System\Library\Response();

foreach ($config->get('response_header') as $header) {
	$response->addHeader($header);
}

$response->setCompression($config->get('response_compression'));
$registry->set('response', $response);

// Database
if ($config->get('db_autostart')) {
	$db = new \Opencart\System\Library\DB($config->get('db_engine'), $config->get('db_hostname'), $config->get('db_username'), $config->get('db_password'), $config->get('db_database'), $config->get('db_port'));
	$registry->set('db', $db);

	// Sync PHP and DB time zones
	$db->query("SET time_zone = '" . $db->escape(date('P')) . "'");
}

// Cache
$registry->set('cache', new \Opencart\System\Library\Cache($config->get('cache_engine'), $config->get('cache_expire')));

// Template
$template = new \Opencart\System\Library\Template($config->get('template_engine'));
$template->addPath(DIR_TEMPLATE);
$registry->set('template', $template);

// Language
$language = new \Opencart\System\Library\Language($config->get('language_code'));
$language->addPath(DIR_LANGUAGE);
$language->load($config->get('language_code'));
$registry->set('language', $language);

// Action error object to execute if any other actions can not be executed.
$error = new \Opencart\System\Engine\Action($config->get('action_error'));

$action = '';

// Pre Actions
foreach ($config->get('action_pre_action') as $pre_action) {
	$pre_action = new \Opencart\System\Engine\Action($pre_action);

	$result = $pre_action->execute($registry);

	if ($result instanceof \Opencart\System\Engine\Action) {
		$action = $result;

		break;
	}

	// If action can not be executed then we return an action error object.
	if ($result instanceof \Exception) {
		$action = $error;

		$error = '';
		break;
	}
}

// Route
if (!$action) {
	if (!empty($request->get['route'])) {
		$action = new \Opencart\System\Engine\Action((string)$request->get['route']);
	} else {
		$action = new \Opencart\System\Engine\Action($config->get('action_default'));
	}
}

// Dispatch
while ($action) {
	// Get the route path of the object to be executed.
	$route = $action->getId();

	$args = [];

	// Keep the original trigger.
	$trigger = $action->getId();

	$event->trigger('controller/' . $trigger . '/before', [&$route, &$args]);

	// Execute the action.
	$result = $action->execute($registry, $args);

	$action = '';

	if ($result instanceof \Opencart\System\Engine\Action) {
		$action = $result;
	}

	// If action can not be executed then we return the action error object.
	if ($result instanceof \Exception) {
		$action = $error;

		// In case there is an error we don't want to infinitely keep calling the action error object.
		$error = '';
	}

	$event->trigger('controller/' . $trigger . '/after', [&$route, &$args, &$output]);
}

// Output
$response->output();