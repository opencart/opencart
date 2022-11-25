<?php
// Autoloader
$autoloader = new \Opencart\System\Engine\Autoloader();
$autoloader->register('Opencart\\' . APPLICATION, DIR_APPLICATION);
$autoloader->register('Opencart\Extension', DIR_EXTENSION);
$autoloader->register('Opencart\System', DIR_SYSTEM);

require_once(DIR_SYSTEM . 'vendor.php');

// Registry
$registry = new \Opencart\System\Engine\Registry();
$registry->set('autoloader', $autoloader);

// Config
$config = new \Opencart\System\Engine\Config();
$registry->set('config', $config);
$config->addPath(DIR_CONFIG);

// Load the default config
$config->load('default');
$config->load(strtolower(APPLICATION));

// Set the default application
$config->set('application', APPLICATION);

// Set the default time zone
date_default_timezone_set($config->get('date_timezone'));

// Logging
$log = new \Opencart\System\Library\Log($config->get('error_filename'));
$registry->set('log', $log);

// Error Handler
set_error_handler(function(string $code, string $message, string $file, string $line) use ($log, $config) {
	// error suppressed with @
	if (@error_reporting() === 0) {
		return false;
	}

	switch ($code) {
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

	if ($config->get('error_log')) {
		$log->write('PHP ' . $error . ':  ' . $message . ' in ' . $file . ' on line ' . $line);
	}

	if ($config->get('error_display')) {
		echo '<b>' . $error . '</b>: ' . $message . ' in <b>' . $file . '</b> on line <b>' . $line . '</b>';
	} else {
		header('Location: ' . $config->get('error_page'));
		exit();
	}

	return true;
});

// Exception Handler
set_exception_handler(function(\Throwable $e) use ($log, $config)  {
	if ($config->get('error_log')) {
		$log->write(get_class($e) . ':  ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
	}

	if ($config->get('error_display')) {
		echo '<b>' . get_class($e) . '</b>: ' . $e->getMessage() . ' in <b>' . $e->getFile() . '</b> on line <b>' . $e->getLine() . '</b>';
	} else {
		header('Location: ' . $config->get('error_page'));
		exit();
	}
});

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

// Compatibility
if (isset($request->get['route'])) {
	$request->get['route'] = str_replace('|', '.', $request->get['route']);
	$request->get['route'] = str_replace('%7C', '|', (string)$request->get['route']);
}

// Response
$response = new \Opencart\System\Library\Response();
$registry->set('response', $response);

foreach ($config->get('response_header') as $header) {
	$response->addHeader($header);
}

$response->addHeader('Access-Control-Allow-Origin: *');
$response->addHeader('Access-Control-Allow-Credentials: true');
$response->addHeader('Access-Control-Max-Age: 1000');
$response->addHeader('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding');
$response->addHeader('Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE');
$response->addHeader('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
$response->addHeader('Pragma: no-cache');
$response->setCompression($config->get('response_compression'));

// Database
if ($config->get('db_autostart')) {
	$db = new \Opencart\System\Library\DB($config->get('db_engine'), $config->get('db_hostname'), $config->get('db_username'), $config->get('db_password'), $config->get('db_database'), $config->get('db_port'));
	$registry->set('db', $db);

	// Sync PHP and DB time zones
	$db->query("SET `time_zone` = '" . $db->escape(date('P')) . "'");
}

// Session
if ($config->get('session_autostart')) {
	$session = new \Opencart\System\Library\Session($config->get('session_engine'), $registry);
	$registry->set('session', $session);

	if (isset($request->cookie[$config->get('session_name')])) {
		$session_id = $request->cookie[$config->get('session_name')];
	} else {
		$session_id = '';
	}

	$session->start($session_id);

	// Require higher security for session cookies
	$option = [
		'expires'  => 0,
		'path'     => $config->get('session_path'),
		'domain'   => $config->get('session_domain'),
		'secure'   => $request->server['HTTPS'],
		'httponly' => false,
		'SameSite' => $config->get('session_samesite')
	];

	setcookie($config->get('session_name'), $session->getId(), $option);
}

// Cache
$registry->set('cache', new \Opencart\System\Library\Cache($config->get('cache_engine'), $config->get('cache_expire')));

// Template
$template = new \Opencart\System\Library\Template($config->get('template_engine'));
$registry->set('template', $template);
$template->addPath(DIR_TEMPLATE);

// Language
$language = new \Opencart\System\Library\Language($config->get('language_code'));
$registry->set('language', $language);
$language->addPath(DIR_LANGUAGE);
$loader->language('default');

// Url
$registry->set('url', new \Opencart\System\Library\Url($config->get('site_url')));

// Document
$registry->set('document', new \Opencart\System\Library\Document());

// Action error object to execute if any other actions cannot be executed.
$action = '';
$args = [];
$output = '';

$error = new \Opencart\System\Engine\Action($config->get('action_error'));

// Pre Actions
foreach ($config->get('action_pre_action') as $pre_action) {
	$pre_action = new \Opencart\System\Engine\Action($pre_action);

	$result = $pre_action->execute($registry);

	if ($result instanceof \Opencart\System\Engine\Action) {
		$action = $result;

		break;
	}

	// If action cannot be executed, we return an action error object.
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
	// Route needs to be updated each time so it can trigger events
	$route = $action->getId();

	// Keep the original trigger.
	$trigger = $route;

	$result = $event->trigger('controller/' . $trigger . '/before', [&$route, &$args]);

	if ($result instanceof \Opencart\System\Engine\Action) {
		$action = $result;
	}

	// Execute the action.
	$result = $action->execute($registry, $args);

	$action = '';

	if ($result instanceof \Opencart\System\Engine\Action) {
		$action = $result;
	}

	// If action cannot be executed, we return the action error object.
	if ($result instanceof \Exception) {
		$action = $error;

		// In case there is an error we don't want to infinitely keep calling the action error object.
		$error = '';
	}

	// If not an object, then it's the output
	if (!$action) {
		$output = $result;
	}

	$result = $event->trigger('controller/' . $trigger . '/after', [&$route, &$args, &$output]);

	if ($result instanceof \Opencart\System\Engine\Action) {
		$action = $result;
	}
}

// Output
$response->output();
